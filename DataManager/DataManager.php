<?php

namespace NoMess\DataManager;

use Closure;
use Exception;
use Throwable;
use SimpleXMLElement;
use NoMess\Exception\WorkException;
use NoMess\HttpRequest\HttpRequest;
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
     * Nombre de transaction lancé
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
     * Stock temporairement les dépendace de l'objet en cours de traitement
     *
     * @var array|null
     */
    private $dependancy;


    /**
     * Stock les configuration spécifique a cette transaction
     *
     * @var array|null
     */
    private $runtime;



    /**
     * @Inject
     *
     * @param ContainerInterface $ci 
     */
    public function __construct(ContainerInterface $ci){
        $this->container = $ci;
    }


    /**
     * Parcour les requêtes et initialise les transactions
     *
     * @return bool
     */
    public function database() : bool
    {
        if(!isset($this->container->get(HttpRequest::class)->getData()['error']) && isset($_SESSION['nomess_db'])){

            $this->getCache();

            //Ouverture d'une connection 
            $this->getConnection();

            $this->connection->beginTransaction();

            foreach($_SESSION['nomess_db'] as $value){
                
                //$param = paramêtres passé
                foreach($value['request'] as $key => $param){
                    
                    $this->key = $key;
                    $this->dependancy = $value['depend'];
                    $this->runtime = $value['runtimeConfig'];

                    //Charge l'objet parent
                    try{                        
                        $this->getMethod($key);

                        $this->doTransaction($param);
                        
                        //charge les objets encapsulé
                        $this->explorer();
                        
                        
                    }catch(\Throwable $e){

                        //Si il y a une erreur, annulation des transations
                        $this->connection->rollBack();
                        unset($_SESSION['nomess_db']);

                        if($this->definition !== null){
                            throw new WorkException('La transation ' . (string)$this->definition->base->attributes()['class'] .  '->' . $this->method . '() à échoué<br><br><br><span>Line ' . $e->getLine() . ' in ' . str_replace(ROOT, '', $e->getFile()) . '<br> ' . $e->getMessage() . '</span>');
                        }else{
                            throw new WorkException('<span>Line ' . $e->getLine() . ' in ' . str_replace(ROOT, '', $e->getFile()) . '<br> ' . $e->getMessage() . '</span>');
                        }
                        
                        return false;
                    }
                }
            }

            //Si tout s'est bien passé, validation de la transaction
            $this->connection->commit();
            $this->sessionCommit();
            unset($_SESSION['nomess_db']);
            return true;
        }

        unset($_SESSION['nomess_db']);

        return true;
    }



    /**
     *
     * @return void
     */
    private function getCache() : void
    {
        $this->cache = simplexml_load_file(self::CONFIG);
    }


    /**
     * récupère une connexion
     *
     * @return void
     */
    private function getConnection() : void
    {
        $factory = $this->container->get(self::DATABASE);
        $this->connection =  $factory->getConnection();
    }


    /**
     * Récupère le nom de la class à persister
     *
     * @param array $req
     *
     * @return string|null
     */
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


    /**
     * Insert les données qui retourne de la function de persistance
     *
     * @param string|null $back
     *
     * @return void
     */
    private function insert(?string $back) : void
    {
        if($back !== null){
            if((string)$this->definition->base->insert !== null){
                $insert = (string)$this->definition->base->insert;


                //Ordre de non insertion
                $runtimeConfig = (isset($this->runtime['insert'][$insert])) ? $this->runtime['insert'][$insert] : null;
                
                if($runtimeConfig !== false){
                    //si privé
                    if(strpos($insert, 'set') !== false){
                        $get = str_replace('set', 'get', $insert);

                        try{
                            if($this->object->$get() === null || $this->object->$get() === 0 || $this->object->$get() === 0.0){
                                throw new \TypeError();
                            }
                        }catch(\TypeError $e){
                            $this->object->$insert($back);
                        }

                    //Si public    
                    }else{
                        
                        if($this->object->$insert === null){
                            $this->object->$insert = $back;
                        }
                    }
                }

                $this->runtimeConfigInsert($back, $insert);
            }
        }

        $this->runtimeConfigInsert($back);
    }


    /**
     * Gere la configuration à l'éxecution pour l'insertion
     *
     * @param [type] $back
     * @param string|null $insert
     *
     * @return void
     */
    private function runtimeConfigInsert($back, ?string $insert = null) : void
    {
        if(isset($this->runtime['insert'])){

            foreach($this->runtime['insert'] as $method => $value){

                if($method === $insert && $value !== false){
                    
                    if(strpos($method, 'set') !== false){//Si privé

                        //Si on insert le retour de la transaction
                        if($value === 'nomess_backTransaction'){
                            $this->object->$method($back);
                        }else{
                            $this->object->$method($value);
                        }
                    }else{//Si public

                        if($value === 'nomess_backTransaction'){
                            $this->object->$method = $back;
                        }else{
                            $this->object->$method = $value;
                        }
                    }
                }else if($method !== $insert){

                    if(strpos($method, 'set') !== false){//Si privé

                        //Si on insert le retour de la transaction
                        if($value === 'nomess_backTransaction'){
                            $this->object->$method($back);
                        }else{
                            $this->object->$method($value);
                        }
                    }else{//Si public

                        if($value === 'nomess_backTransaction'){
                            $this->object->$method = $back;
                        }else{
                            $this->object->$method = $value;
                        }
                    }
                }
            }
        }
    }



    /**
     * Cherche une corrélation entre le nom de la class recu et les données 'unregister' ou le tableau de dépendance
     *
     * @param string $className
     *
     * @return Object|null
     */
    private function getCorrelation(string $className) : ?Object
    {

        $backObject = null;

        if(get_class($this->object) === $className){
            return $this->object;
        }

        if($this->dependancy !== null){
            foreach($this->dependancy as $value){
                if(get_class($value) === $className){
                    return $value;
                }
            }
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


    /**
     * Insert les données nécessaire à l'objet avant la fonction de persistance
     *
     * @return void
     */
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

                    //Si redéfinie = non insertion
                    if(!isset($this->runtime['depend'][$set])){
                        //si privé
                        if(strpos($set, 'set') !== false){

                            $reverse = str_replace('set', 'get', $set);
                            
                            try{
                                if($this->object->$reverse() === null || $this->object->$reverse() === 0 || $this->object->$reverse() === 0.0){
                                    throw new \TypeError();
                                }
                            }catch(\TypeError $e){

                                if(strpos($get, 'get') !== false){

                                    $this->object->$set($object->$get());
                                }else{
                                    $this->object->$set($object->$get);
                                } 
                            }
                            
                        //Si public
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

        $this->runtimeConfigDepend();
    }


    /**
     * Gère la configuration à l'éxecution de depend
     *
     * @param array $setting
     *
     * @return void
     */
    private function runtimeConfigDepend() : void
    {
        if(isset($this->runtime['depend'])){
            foreach($this->runtime['depend'] as $setter => $classNameMethod){
                if($classNameMethod !== false){
                    $tmp = explode('::', $classNameMethod);

                    $object = $this->getCorrelation($tmp[0]);
            
                    if($object !== null){
                    
                        if(!isset($tmp[1])){
                            throw new WorkException('Erreur de syntaxe: la dépendence "' . $tmp[0] . '" ne contient pas de methode');
                        }

                        if(strpos($tmp[1], '()')){
                            throw new WorkException('Erreur de syntaxe: la dépendence "' . $tmp[0] . '" contient une methode invalide.<br>Unxcepted "()"');
                        }

                        $get = $tmp[1];

                        //si privé
                        if(strpos($setter, 'set') !== false){


                            if(strpos($get, 'get') !== false){

                                $this->object->$setter($object->$get());
                            }else{
                                $this->object->$setter($object->$get);
                            } 
                            
                        //Si public
                        }else{

                            if(strpos($get, 'get') !== false){

                                $this->object->$setter = $object->$get();
                            }else{
                                
                                $this->object->$setter = $object->$get;
                            }
                        }

                    }else{
                        //si privé
                        if(strpos($setter, 'set') !== false){

                            $this->object->$setter($classNameMethod);
                            
                        //Si public
                        }else{
                            $this->object->$setter = $classNameMethod;
                            
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


                //Ordre prioritaire de non transaction
                $runtime = (isset($this->runtime['transaction'][get_class($value)])) ? $this->runtime['transaction'][get_class($value)] : null;

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

                    if((empty($noCreate) || $runtime === true) && $runtime !== false){
                        $this->doTransaction([$value]);
                    }
                }else if($this->method === 'update' || $alias === 'update'){
                    $noUpdate = (string)$xmlProperty['noUpdate'];

                    if((empty($noUpdate) || $runtime === true) && $runtime !== false){
                        $this->doTransaction([$value]);
                    }
                }else if($this->method === 'delete' || $alias === 'delete'){
                    $noDelete = (string)$xmlProperty['noDelete'];

                    if((empty($noDelete) || $runtime === true) && $runtime !== false){
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

        $delete = false;

        foreach($this->definition->base->alias as $value){
            if((string)$value->attributes()['alias'] === $this->method 
                && (string)$value->attributes()['method'] === 'delete'){

                $delete = true;
            }
        }

        if($this->method === 'delete'){
            $delete = true;
        }

        if(!empty($sessionKey)){
            $keyArray = (string)$this->definition->session->keyArray;

            if(!empty($keyArray)){
                if(strpos($keyArray, 'get') !== false){
                    try{
                        if($delete === false){
                            $this->unregister[$sessionKey][$this->object->$keyArray()] = $this->object;
                        }else{
                            $this->unregister[$sessionKey][$this->object->$keyArray()] = '&delete&';
                        }
                    }catch(Throwable $e){
                        throw new WorkException($e->getMessage() . '<br><br>Controllez:<br>- La syntaxe des annotations<br>- Le retour de la methode de persistance<br>- Le typage de la fonction et son existance');
                    }
                }else{
                    if($delete === false){
                        $this->unregister[$sessionKey][$this->object->$keyArray] = $this->object;
                    }else{
                        $this->unregister[$sessionKey][$this->object->$keyArray] = '&delete&';
                    }
                }
            }else{
                if($delete === false){
                    $this->unregister[$sessionKey] = $this->object;
                }else{
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
    private function sessionCommit() : void
    {        

        foreach($this->unregister as $sessionKey => $value){

            if(is_array($value)){
                foreach($value as $keyArray => $object){
                    if(is_object($object)){
                        $_SESSION[$sessionKey][$keyArray] = $object;
                    }else{
                        unset($_SESSION[$sessionKey][$keyArray]);
                    }
                }
            }else{
                if(is_object($value)){
                    $_SESSION[$sessionKey] = $value;
                }else{
                    unset($_SESSION[$sessionKey]);
                }
            }
        }
    }
}