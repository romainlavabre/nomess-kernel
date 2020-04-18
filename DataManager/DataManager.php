<?php

namespace NoMess\DataManager;

use Closure;
use TypeError;
use ReflectionClass;
use SimpleXMLElement;
use ReflectionProperty;
use NoMess\Exception\WorkException;
use Psr\Container\ContainerInterface;

class DataManager
{
    const CONFIG    = ROOT . 'App/var/cache/mondata.xml';
    const DATABASE  = 'NoMess\Database\IPDOFactory';

    /**
     * Contient les Objects pour lesquels une transaction à eu lieu mais non enregistré en session
     *
     * @var array
     */
    private $unregister = array();


    /**
     * Connexion à la base
     *
     * @var \PDO
     */
    private $connection;


    /**
     * Contient le fichier de cache 
     *
     * @var SimpleXMLElement
     */
    private $cache;

    /**
     * Contient la définition de la class demandé
     *
     * @var SimpleXmlElement
     */
    private $definition;

    /**
     * Contient l'objet courant
     *
     * @var Object
     */
    private $object;

    /**
     * Methode à appeler
     *
     * @var string
     */
    private $method;


    /**
     * Contient la reqete
     *
     * @var string
     */
    private $key;

    /**
     * Instance de ContainerInterface
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Nombre de transavtion lancé
     *
     * @var int
     */
    private $iterate = 1;

    /**
     * Gère le traitement des sous objects
     *
     * @var Closure
     */
    private $work;

    /**
     * @Inject
     *
     * @param ContainerInterface $ci 
     */
    public function __construct(ContainerInterface $ci){
        $this->container = $ci;
    }


    
    public function database() : void
    {
        if(!isset($_SESSION['nomess_attribute']['error']) && isset($_SESSION['nomess_db'])){

            $this->getCache();

            //Ouverture d'une connection 
            $this->getConnection();

            $this->connection->beginTransaction();

            foreach($_SESSION['nomess_db'] as $value){
                
                //$param = paramêtres passé
                foreach($value as $key => $param){
                    
                    $this->key = $key;

                    
                    //Charge l'objet parent
                    try{
                        
                        $this->getMethod($key);

                        $this->doTransaction($param);
                        
                        //charge les objets encapsulé
                        $this->explorer();
                        
                        
                    }catch(\Exception $e){

                        //Si il y a une erreur, annulation des transations
                        $this->connection->rollBack();
                        unset($_SESSION['nomess_db']);
                        unset($_SESSION['nomess_attribute']);
                        unset($_SESSION['nomess_render']);

                        if($this->definition !== null){
                            throw new WorkException('La transation ' . (string)$this->definition->base->attributes()['class'] .  '->' . $this->method . '() à échoué<br><br><br><span style="font-size: 20px">' . $e->getMessage() . '</span>');
                        }else{
                            throw new WorkException('Une transaction à échoué: <br><br><span style="font-size: 20px">' . $e->getMessage() . '</span>');
                        }
                        
                        die;
                    }
                }
            }

            //Si tout s'est bien passé, validation de la transaction
            $this->connection->commit();
            $this->sessionCommit();
        }
    }

    private function getCache() : void
    {
        $this->cache = simplexml_load_file(self::CONFIG);
    }

    private function getConnection() : void
    {
        $factory = $this->container->get(self::DATABASE);
        $this->connection =  $factory->getConnection();
    }

    private function getClassName(array $req) : ?string
    {
        $tabKey = explode(':', $this->key);

        // Définie le type de la class 
        if(isset($tabKey[1]) && !empty($tabKey[1])){
            foreach($req as $param){
                if(is_object($param)){
                    if(get_class($param) === $tabKey[1]){
                        $this->object = $param;
                        break;
                    }
                }
            }

            return $tabKey[1];
        }else{
            foreach($req as $param){
                if(is_object($param)){
                    $temp = get_class($param);

                    if($temp !== null){
                        $this->object = $param;
                        return $temp;
                    }else{
                        return null;
                    }
                                
                    break;
                }
            }
        }

        return null;
    }

    /**
     * Retourne la methode de class table
     *
     * @param string $req
     * @return string
     */
    private function getMethod(string $req) : void
    {
        $tab = explode(':', $req);

        $this->method = $tab[0];
    }


    /**
     * Chercher une définition pour Object dans le cache
     *
     * @param string $type
     * @return SimpleXMLElement|null
     */
    private function getDefinition(string $type) : ?SimpleXMLElement
    {
        $tabDef = array();
        
        foreach($this->cache->class as $value){
            if((string)$value->attributes()['class'] === $type){

                return $value;
            }
        }

        return null;
    }

    private function insert(?string $back) : void
    {
        if($back !== null){
            if((string)$this->definition->base->insert !== null){
                $insert = (string)$this->definition->base->insert;
                
                //si privé
                if(strpos($insert, 'set') !== false){
                    $get = str_replace('set', 'get', $insert);

                    try{
                        if($this->object->$get() === null){
                            throw new \TypeError();
                        }
                    }catch(\TypeError $e){
                        
                        $this->object->$insert($back);
                    }
                    
                }else{
                    
                    if($this->object->$insert === null){
                        $this->object->$insert = $back;
                    }
                }
            }
        }
    }

    private function getCorrelation(string $className) : ?Object
    {

        $backObject = null;

        if(get_class($this->object) === $className){
            return $this->object;
        }

        foreach($this->unregister as $value){

            if(is_object($value)){
                if(get_class($value) === $className){
                    $backObject = $value;
                }
            }else{
                foreach($value as $object){
                    if(is_object($object) && get_class($object) === $className){
                        $backObject = $object;
                    }
                }
            }
        }

        return $backObject;
    }

    private function depend() : void
    {
        $nbrDepend = count($this->definition->base->depend);

        if($nbrDepend > 0){
            foreach($this->definition->base->depend as $value){
                $className = (string)$value->attributes()['class'];

                $object = $this->getCorrelation($className);
            
                if($object !== null){
                   
                    $set = (string)$value->attributes()['set'];
                    $get = (string)$value->attributes()['get'];

                    //si privé
                    if(strpos($set, 'set') !== false){

                        $reverse = str_replace('set', 'get', $set);
                        
                        try{
                            if($this->object->$reverse() === null){
                                throw new \TypeError();
                            }
                        }catch(\TypeError $e){

                            if(strpos($get, 'get') !== false){

                                $this->object->$set($object->$get());
                            }else{
                                $this->object->$set($object->$get);
                            } 
                        }
                        
                    }else{

                        if($this->object->$set === null){

                            if(strpos($get, 'get') !== false){

                                $this->object->$set = $object->$get();
                            }else{
                                
                                $this->object->$set = $object->$get;
                            }
                        }
                    }
                }
            }
        }
    }


    /**
     * Lis les attributs d'un class
     *
     * @return closure
     */
    private function closureGetVar() : closure 
    {
        return function () : array
        {
            return get_object_vars($this)  ;
        };
    }


    /**
     * Effectue une transaction avec la base de donnée
     *
     * @param string $key
     * @param array $param
     * @return void
     */
    private function doTransaction(array $param) : void
    {
        
        //Récupère le nom de la class (avec namespace)
        $type = $this->getClassName($param);

        if($type === null){
            throw new WorkException('Aucune class valide trouvé pour la requête ' . $this->key);
            die();
        }

        //Récupère les définition à appliquer pour cette class
        $definition = $this->getDefinition($type);

        if($definition !== null){
            $this->definition = $definition;

            $this->depend();

            $back = call_user_func_array(array(
                $this->container->get(
                    (string)$this->definition->base->attributes()['class']), 
                    $this->method), 
                $param
            );

            $this->insert($back);
                    
            $this->sessionStack();

            $this->iterate++;
            
        }else if($this->iterate === 1){

            throw new WorkException('Aucune définition trouvé pour ' . $type);
            die();   
        }
    }


    /**
     * Travaille sur les objets encapsulé de l'objet courant
     *
     * @return void
     */
    private function explorer() : void
    {

        $this->work = function (&$value, $key, $supervisorKey = null)
        {

            if(is_object($value)){

                $xmlProperty = null;
                         
                foreach($this->definition->base->transaction as $xmlProp){
                    if($supervisorKey === null){
                        if((string)$xmlProp->attributes()['name'] === $key){
                            $xmlProperty = $xmlProp;
                        }
                    }else{
                        if((string)$xmlProp->attributes()['name'] === $supervisorKey){
                            $xmlProperty = $xmlProp;
                        }
                    }
                }

                $alias = null;

                foreach($this->definition->alias as $value){
                    if((string)$value->attributes()['method'] === $this->method){
                        $alias = (string)$value->attributes()['alias'];
                    }
                }
                
                
                if($this->method === 'create' || $alias === 'create'){
                    $noCreate = (string)$xmlProperty['noCreate'];

                    if(empty($noCreate)){
                        $this->doTransaction([$value]);
                    }
                }else if($this->method === 'update' || $alias === 'update'){
                    $noUpdate = (string)$xmlProperty['noUpdate'];

                    if(empty($noUpdate)){
                        $this->doTransaction([$value]);
                    }
                }else if($this->method === 'delete' || $alias === 'delete'){
                    $noDelete = (string)$xmlProperty['noDelete'];

                    if(empty($noDelete)){
                        $this->doTransaction([$value]);
                    }
                }

                $properties = $this->closureGetVar()->call($value);
           
                array_walk($properties, $this->work);
            }else if(is_array($value)){
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
    private function sessionStack() : void
    {
        $sessionKey = (string)$this->definition->session->key;

        if(!empty($sessionKey)){
            $keyArray = (string)$this->definition->session->keyArray;

            if(!empty($keyArray)){
                if(strpos($keyArray, 'get') !== false){
                    $this->unregister[$sessionKey][$this->object->$keyArray()] = $this->object;
                }else{
                    $this->unregister[$sessionKey][$this->object->$keyArray] = $this->object;
                }
            }else{
                $this->unregister[$sessionKey] = $this->object;
            }
        }

    }

    private function sessionCommit() : void
    {        

        foreach($this->unregister as $sessionKey => $value){

            if(is_array($value)){
                foreach($value as $keyArray => $object){
                    
                    $_SESSION[$sessionKey][$keyArray] = $object;
                }
            }else{
                $_SESSION[$sessionKey] = $value;
            }
        }
    }
}