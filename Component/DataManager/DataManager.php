<?php

namespace NoMess\Component\DataManager;

use Closure;
use NoMess\Component\PersistsManager\PersistsManager;
use NoMess\Container\Container;
use NoMess\Database\IPDOFactory;
use NoMess\Exception\WorkException;
use NoMess\HttpRequest\HttpRequest;
use NoMess\HttpSession\HttpSession;
use NoMess\Service\DataCenter;
use SimpleXMLElement;
use Throwable;


class DataManager
{
    private const CONFIG            = ROOT . 'App/var/cache/dm/datamanager.xml';

    /**
     * Contains object has incur treatment but hasn't push in session
     */
    private array $unregister = array();


    /**
     * Current connection to database server
     */
    private \PDO $connection;


    /**
     * Contains cache file
     */
    private SimpleXMLElement $cache;

    /**
     * Contains the defintion for current object
     */
    private SimpleXMLElement $definition;

    /**
     * Contains current object
     */
    private object $object;

    /**
     * Method to call
     */
    private string $method;

    /**
     * Contains current request method
     */
    private string $key;

    private Container $container;

    /**
     * Number of transaction throw (internal data for process)
     *
     * @var int
     */
    private int $iterate = 1;

    /**
     *
     * Manage treatment of encapsuled object
     */
    private Closure $work;


    /**
     * Stock temporarly the dependance of current object
     */
    private ?array $dependancy;


    /**
     * Stock a spécific configuration for this transaction
     */
    private ?array $runtime;


    /**
     * Stock an array instance of PersistsManager for current transaction
     *
     * @var array|bool|null
     */
    private $persistsManager;


    /**
     * @Inject
     *
     * @param Container $ci
     */
    public function __construct(Container $ci)
    {
        $this->container = $ci;
    }


    /**
     * @param array $data
     * @param string $idConfigDatabase
     * @return bool
     * @throws WorkException
     */
    public function database(array $data, string $idConfigDatabase): bool
    {

        if (!isset($this->container->get(HttpRequest::class)->getData()['error'])) {


            //Get cache file
            $this->getCache();

            //Open a connection to database with specific (or not) configuration
            $this->getConnection($idConfigDatabase);

            $this->connection->beginTransaction();

            foreach ($data as $value) {

                /*
                 * $value contains all request to treat
                 * Request
                 * Dependend
                 * runtime configuration
                 * persistsManager
                 */
                foreach ($value['request'] as $key => $param) {

                    $this->key = $key;
                    $this->dependancy = $value['depend'];
                    $this->runtime = $value['runtimeConfig'];
                    $this->persistsManager = $value['persistsManager'];

                    //Launch transaction
                    try {
                        $this->getMethod($key);

                        $this->doTransaction($param);
                        //charge les objets encapsulé
                        $this->explorer();



                    } catch (\Throwable $e) {

                        //If an error occured, rollback
                        $this->connection->rollBack();

                        if(NOMESS_CONTEXT === 'DEV') {

                            if ($this->definition !== null) {
                                throw new WorkException('La transation ' . (string)$this->definition->base->attributes()['class'] . '->' . $this->method . '() à échoué<br><br><br><span>Line ' . $e->getLine() . ' in ' . str_replace(ROOT, '', $e->getFile()) . '<br> ' . $e->getMessage() . '</span>');
                            } else {
                                throw new WorkException('<span>Line ' . $e->getLine() . ' in ' . str_replace(ROOT, '', $e->getFile()) . '<br> ' . $e->getMessage() . '</span>');
                            }

                        }else{

                            $request = $this->container->get(HttpRequest::class);
                            $datacenter = $this->container->get(DataCenter::class);

                            $message = $datacenter->getData('error_data_manager');

                            if($message === null){
                                $request->setError('Une erreur s\'est produite');
                            }else{
                                $request->setError($message);
                            }

                            $request->resetSuccess();

                        }

                        return false;
                    }
                }
            }

            //If hasn't error, commit in database and session
            $this->connection->commit();
            $this->sessionCommit();
            return true;
        }

        return true;
    }


    /**
     *
     * @return void
     */
    private function getCache(): void
    {
        $this->cache = simplexml_load_file(self::CONFIG);
    }


    /**
     * Get an connection
     *
     * @return void
     */
    private function getConnection(): void
    {
        $factory = $this->container->get(IPDOFactory::class);
        $this->connection = $factory->getConnection();
    }


    /**
     * Get current class name
     *
     * @param array $req
     *
     * @return string|null
     */
    private function getClassName(array $req): ?string
    {
        $tabKey = explode(':', $this->key);

        //if type of class is explicitly specified
        if (isset($tabKey[1]) && !empty($tabKey[1])) {
            foreach ($req as $param) {
                if (is_object($param)) {
                    if (get_class($param) === $tabKey[1]) {
                        $this->object = $param;
                        break;
                    }
                }
            }

            return $tabKey[1];
        } else {
            foreach ($req as $param) {
                if (is_object($param)) {
                    $temp = get_class($param);

                    if ($temp !== null) {
                        $this->object = $param;
                        return $temp;
                    } else {
                        return null;
                    }

                    break;
                }
            }
        }

        return null;
    }


    /**
     * return a correct method for transaction
     *
     * @param string $req
     * @return string
     */
    private function getMethod(string $req): void
    {
        $tab = explode(':', $req);

        $this->method = $tab[0];
    }


    /**
     * Search an definition for this object in cache file
     *
     * @param string $type
     * @return SimpleXMLElement|null
     */
    private function getDefinition(string $type): ?SimpleXMLElement
    {
        $tabDef = array();

        foreach ($this->cache->class as $value) {
            if ((string)$value->attributes()['class'] === $type) {

                return $value;
            }
        }

        return null;
    }


    /**
     * Insert data returned by transaction
     *
     * @param string|null $back
     *
     * @return void
     */
    private function insert(?string $back): void
    {
        if ($back !== null) {
            if ((string)$this->definition->base->insert !== null) {
                $insert = (string)$this->definition->base->insert;

                $runtimeConfig = (isset($this->runtime['insert'][$insert])) ? $this->runtime['insert'][$insert] : null;


                //If this insertion is redefined, non insertion (runtimeConfig function will be work)
                if ($runtimeConfig !== false) {

                    $this->pushData($insert, $back, true);
                }

                $this->runtimeConfigInsert($back, $insert);
            }
        }

        $this->runtimeConfigInsert($back);
    }


    /**
     * Manage runtime configuration for this option
     *
     * @param mixed $back
     * @param string|null $insert
     *
     * @return void
     */
    private function runtimeConfigInsert($back, ?string $insert = null): void
    {
        if (isset($this->runtime['insert'])) {

            foreach ($this->runtime['insert'] as $method => $value) {

                if ($value !== false) {

                    //If value is equals to this, insert return of transaction, else insert arbitrary mixed value
                    if ($value === 'nomess_backTransaction') {
                        $this->pushData($method, $back);
                    } elseif (is_array($value) && is_string(key($value))) {//If parameter is an object array to defer taken value

                        foreach ($value as $key => $array) {

                            foreach ($depend as $get => $array) {

                                if (is_array($array)) {
                                    foreach ($array as $object) {
                                        $this->pushData($setter, $this->pullData($get, $object));
                                    }
                                } else {
                                    $this->pushData($setter, $this->pullData($get, $array));
                                }
                            }
                        }
                    } else {
                        $this->pushData($method, $value);
                    }
                }
            }
        }
    }


    /**
     *
     * Search an class name dependency and that data are not saved or dependency array
     *
     * @param string $className
     *
     * @return Object|null
     */
    private function getCorrelation(string $className): ?object
    {

        $backObject = null;

        if (get_class($this->object) === $className) {
            return $this->object;
        }

        if ($this->dependancy !== null) {
            foreach ($this->dependancy as $value) {
                if (get_class($value) === $className) {
                    return $value;
                }
            }
        }

        foreach ($this->unregister as $value) {

            if (is_object($value)) {
                if (get_class($value) === $className) {
                    $backObject = $value;
                }
            } else {
                foreach ($value as $object) {
                    if (is_object($object) && get_class($object) === $className) {
                        $backObject = $object;
                    }
                }
            }
        }

        return $backObject;
    }


    /**
     * Insert the dependency data necessary for the current object
     *
     * @return void
     */
    private function depend(): void
    {
        $nbrDepend = count($this->definition->base->depend);


        if ($nbrDepend > 0) {
            foreach ($this->definition->base->depend as $value) {
                $className = (string)$value->attributes()['class'];

                $object = $this->getCorrelation($className);

                if ($object !== null) {

                    $set = (string)$value->attributes()['set'];
                    $get = (string)$value->attributes()['get'];

                    //If dependancy is redifined, not insertion, runtimeConfig function will be work for this
                    if (!isset($this->runtime['depend'][$set])) {

                        //Push new data whatever scope
                        $this->pushData($set, $this->pullData($get, $object), true);
                    }
                }
            }
        }

        $this->runtimeConfigDepend();
    }


    /**
     * Manage runtime configuration for depend option
     *
     * @param array $setting
     *
     * @return void
     */
    private function runtimeConfigDepend(): void
    {
        if (isset($this->runtime['depend'])) {
            foreach ($this->runtime['depend'] as $setter => $depend) {
                if ($depend !== false) {

                    $tmp = array();
                    $object = null;

                    try {
                        $tmp = explode('::', $depend);

                        $object = $this->getCorrelation($tmp[0]);
                    } catch (Throwable $th) {
                    }

                    if ($object !== null) {

                        if (!isset($tmp[1])) {
                            throw new WorkException('Erreur de syntaxe: la dépendence "' . $tmp[0] . '" ne contient pas de methode');
                        }

                        if (strpos($tmp[1], '()')) {
                            throw new WorkException('Erreur de syntaxe: la dépendence "' . $tmp[0] . '" contient une methode invalide.<br>Unxcepted "()"');
                        }

                        $get = $tmp[1];


                        //Push data depend whatever scope
                        $this->pushData($setter, $this->pullData($get, $object));

                    } elseif (is_array($depend) && is_string(key($depend))) {//If parameter is an object array to defer taken value

                        foreach ($depend as $get => $array) {

                            if (is_array($array)) {
                                foreach ($array as $object) {
                                    $this->pushData($setter, $this->pullData($get, $object));
                                }
                            } else {
                                $this->pushData($setter, $this->pullData($get, $array));
                            }
                        }
                    } else {//It's arbitrary mixed value
                        //Push data depend whatever scope
                        $this->pushData($setter, $depend);

                    }
                }
            }
        }
    }


    /**
     * Lis les attributs d'une class
     *
     * @return closure
     */
    private function closureGetVar(): closure
    {
        return function (): array {
            return get_object_vars($this);
        };
    }


    /**
     * Effectue une transaction avec la base de donnée
     *
     * @param string $key
     * @param array $param
     * @return void
     */
    private function doTransaction(array $param): void
    {

        //Récupère le nom de la class (avec namespace)
        $type = $this->getClassName($param);

        if ($type === null) {
            throw new WorkException('Aucune class valide trouvé pour la requête ' . $this->key);
            die();
        }

        //Récupère les définition à appliquer pour cette class
        $definition = $this->getDefinition($type);

        if ($definition !== null) {
            $this->definition = $definition;

            $this->depend();


            $back = null;

            if(!isset($this->persistsManager)) {
                $back = call_user_func_array(array(
                    $this->container->get(
                        (string)$this->definition->base->attributes()['class']),
                    $this->method),
                    $param
                );
            }else{

                if(count($param) > 1){
                    throw new WorkException('DataManager encountered an error: when you use a persists manager, pass only object parameter inside database function, the persists manager parameter must be passed to PMBuilder ');
                }

                $method = $this->method;

                $persistsManager = $this->container->make(PersistsManager::class);

                if(is_array($this->persistsManager) && array_key_exists($className, $this->persistsManager)) {
                    $className = get_class($param[0]);


                    $persistsManager->$method(
                        $this->persistsManager[$className]['object'],
                        $this->persistsManager[$className]['parameters'],
                        ($this->persistsManager[$className]['idMethod'] !== null) ? $this->persistsManager[$type]['idMethod'] : $this->method
                    );
                }else{
                    $persistsManager->$method(
                        $param[0]
                    );
                }

                $back = $persistsManager->execute();
            }

            $this->insert($back);

            $this->sessionStack();


            $this->iterate++;

        } else if ($this->iterate === 1) {

            throw new WorkException('Aucune définition trouvé pour ' . $type);
            die();
        }
    }


    /**
     * Travaille sur les objets encapsulé de l'objet courant
     *
     * @return void
     */
    private function explorer(): void
    {

        $this->work = function (&$value, $key, $supervisorKey = null) {

            if (is_object($value)) {


                //Ordre prioritaire de non transaction
                $runtime = (isset($this->runtime['transaction'][get_class($value)])) ? $this->runtime['transaction'][get_class($value)] : null;

                $xmlProperty = null;

                foreach ($this->definition->base->transaction as $xmlProp) {
                    if ($supervisorKey === null) {
                        if ((string)$xmlProp->attributes()['name'] === $key) {
                            $xmlProperty = $xmlProp;
                        }
                    } else {
                        if ((string)$xmlProp->attributes()['name'] === $supervisorKey) {
                            $xmlProperty = $xmlProp;
                        }
                    }
                }

                $alias = null;

                foreach ($this->definition->alias as $value) {
                    if ((string)$value->attributes()['method'] === $this->method) {
                        $alias = (string)$value->attributes()['alias'];
                    }
                }


                if ($this->method === 'create' || $alias === 'create') {
                    $noCreate = (string)$xmlProperty['noCreate'];

                    if ((empty($noCreate) || $runtime === true) && $runtime !== false) {
                        $this->doTransaction([$value]);
                    }
                } else if ($this->method === 'update' || $alias === 'update') {
                    $noUpdate = (string)$xmlProperty['noUpdate'];

                    if ((empty($noUpdate) || $runtime === true) && $runtime !== false) {
                        $this->doTransaction([$value]);
                    }
                } else if ($this->method === 'delete' || $alias === 'delete') {
                    $noDelete = (string)$xmlProperty['noDelete'];

                    if ((empty($noDelete) || $runtime === true) && $runtime !== false) {
                        $this->doTransaction([$value]);
                    }
                }

                $properties = $this->closureGetVar()->call($value);

                array_walk($properties, $this->work);
            } else if (is_array($value)) {
                array_walk($value, $this->work, $key);
            }
        };

        $properties = $this->closureGetVar()->call($this->object);

        array_walk($properties, $this->work);


    }

    /**
     * Stock les données mise a jour ou nouvelle
     *
     * @return void
     */
    private function sessionStack(): void
    {
        $sessionKey = (string)$this->definition->session->key;

        $delete = false;

        foreach ($this->definition->base->alias as $value) {
            if ((string)$value->attributes()['alias'] === $this->method
                && (string)$value->attributes()['method'] === 'delete') {

                $delete = true;
            }
        }

        if ($this->method === 'delete') {
            $delete = true;
        }

        if (!empty($sessionKey)) {
            $keyArray = (string)$this->definition->session->keyArray;

            if (!empty($keyArray)) {

                if (strpos($keyArray, 'get') !== false) {//Si privé
                    try {
                        if ($delete === false) {
                            $this->unregister[$sessionKey][$this->object->$keyArray()] = $this->object;
                        } else {
                            $this->unregister[$sessionKey][$this->object->$keyArray()] = '&delete&';
                        }
                    } catch (Throwable $e) {
                        throw new WorkException($e->getMessage() . '<br><br>Controllez:<br>- La syntaxe des annotations<br>- Le retour de la methode de persistance<br>- Le typage de la fonction et son existance');
                    }
                } else {//Si public
                    if ($delete === false) {
                        $this->unregister[$sessionKey][$this->object->$keyArray] = $this->object;
                    } else {
                        $this->unregister[$sessionKey][$this->object->$keyArray] = '&delete&';
                    }
                }
            } else {
                if ($delete === false) {
                    $this->unregister[$sessionKey] = $this->object;
                } else {
                    $this->unregister[$sessionKey] = '&delete&';
                }
            }
        }

    }

    /**
     * Commit les nouvelles donnée en session
     * (Si la valeur n'est pas un object alors elle est egale a string('&delete&'), alors elle est supprimé)
     *
     * @return void
     */
    private function sessionCommit(): void
    {

        foreach ($this->unregister as $sessionKey => $value) {

            if (is_array($value)) {
                foreach ($value as $keyArray => $object) {
                    if (is_object($object)) {
                        $_SESSION[$sessionKey][$keyArray] = $object;
                    } else {
                        unset($_SESSION[$sessionKey][$keyArray]);
                    }
                }
            } else {
                if (is_object($value)) {
                    $_SESSION[$sessionKey] = $value;
                } else {
                    unset($_SESSION[$sessionKey]);
                }
            }
        }
    }


    /**
     *
     * Push data inside function whatever scope
     *
     *
     * @param string $method
     * @param $data
     * @param bool $control Control that original data is null
     */
    private function pushData(string $method, $data, bool $control = false): void
    {

        if (strpos($method, 'set') !== false) {//Si privé

            if ($control === false) {
                if (is_array($data)) {
                    try {
                        $this->object->$method($data);
                    } catch (Throwable $th) {
                        foreach ($data as $value) {
                            $this->object->$method($value);
                        }
                    }
                } else {
                    $this->object->$method($data);
                }
            } else {
                $reverse = str_replace('set', 'get', $method);

                try {
                    if ($this->object->$reverse() === null || $this->object->$reverse() === 0 || $this->object->$reverse() === 0.0) {
                        throw new \TypeError();
                    }
                } catch (\Throwable $e) {

                    if (is_array($data)) {
                        try {
                            $this->object->$method($data);
                        } catch (Throwable $th) {
                            foreach ($data as $data) {
                                $this->object->$method($data);
                            }
                        }
                    } else {
                        $this->object->$method($data);
                    }
                }
            }
        } else {//Si public

            if ($control === false) {
                $this->object->$method = $value;
            } elseif ($this->object->$method === null) {
                $this->object->$method = $value;
            }
        }
    }


    /**
     * Return data of object whatever scope
     *
     * @param $method
     * @param $object
     * @return mixed
     */
    private function pullData($method, $object)
    {

        $data = null;

        try {
            $data = $object->$method();
        } catch (Throwable $e) {
            $data = $object->$method;
        }


        return $data;
    }
}