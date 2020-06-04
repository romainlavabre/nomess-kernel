<?php

namespace NoMess\Component\PersistsManager;


use NoMess\Component\Component;
use NoMess\Component\PersistsManager\Builder\BuilderPersistsManager;
use NoMess\Component\PersistsManager\ResolverRequest\ResolverCreate;
use NoMess\Component\PersistsManager\ResolverRequest\ResolverDelete;
use NoMess\Component\PersistsManager\ResolverRequest\ResolverSelect;
use NoMess\Component\PersistsManager\ResolverRequest\ResolverUpdate;
use NoMess\Container\Container;
use NoMess\Database\IPDOFactory;
use NoMess\Exception\WorkException;
use NoMess\ObserverInterface;


class PersistsManager extends Component
{

    private const STORAGE_CONFIGURATION = ROOT . 'App/config/component/PersistsManager.php';
    private const STORAGE_CACHE = ROOT . 'App/var/cache/pm/';


    /**
     * Number of loop
     */
    private int $loop = 0;

    /**
     * Contains available configuration
     */
    private array $file;


    /**
     * Contains a configuration for request
     */
    private array $config;


    /**
     * This value is iterated when
     */
    private int $calledGetCache = 0;


    /**
     * Contains reference method
     */
    private string $internalMethod;


    /**
     * Contains alias to method to call
     */
    private string $method;


    /**
     * Contains cursor on configuration (xml file)
     */
    private string $cursorXML;



    /**
     * Contains object to persists
     *
     * @var mixed
     */
    private $object;



    /**
     * Contains parameter of request
     */
    private ?array $parameter;


    /**
     * Contains the class name
     */
    private string $className;



    /**
     * Contains the configuration for object
     */
    private array $cache;


    private IPDOFactory $IPDOFactory;
    private Container $container;


    /**
     * @Inject()
     *
     * PersistsManager constructor.
     * @param IPDOFactory $IPDOFactory
     * @param Container $container
     * @throws \NoMess\Exception\WorkException
     */
    public function __construct(IPDOFactory $IPDOFactory,
                                Container $container)
    {
        parent::__construct();
        $this->IPDOFactory = $IPDOFactory;
        $this->container = $container;
        $this->file = require self::STORAGE_CONFIGURATION;
    }


    /**
     * Initialize an transaction read
     *
     * @param string $fullNameClass Full name of object target
     * @param array|null $parameter Parameters for request, must be an array with ['parameter' => $value]
     * @param string $idMethod id of the method to call, it's 'read' by default
     */
    public function read(string $fullNameClass, ?array $parameter = null, string $idMethod = 'read'): void
    {
        try {
            $this->parameter = $parameter;
            $this->className = $fullNameClass;
            $this->cursorXML = $fullNameClass;
            $this->config = $this->file[$fullNameClass];
        } catch (\Throwable $th) {}

        $this->internalMethod = 'read';
        $this->method = $idMethod;
    }


    /**
     * Initialize an transaction create
     *
     * @param object $object Object target
     * @param array|null $parameter Parameters for request, must be an array with ['parameter' => $value]
     * @param string $idMethod id of the method to call, it's 'create' by default
     */
    public function create(object $object, ?array $parameter = null, string $idMethod = 'create'): void
    {
        try {
            $this->className = get_class($object);
            $parameter[$this->getOnlyClassName($this->className)] = $object;
            $this->parameter = $parameter;
            $this->cursorXML = $this->className;
            $this->config = $this->file[$this->className];
        } catch (\Throwable $th) {}

        $this->internalMethod = 'create';
        $this->method = $idMethod;
        $this->object = $object;

    }


    /**
     * Initialize an transaction update
     *
     * @param object $object Object target
     * @param array|null $parameter Parameters for request, must be an array with ['parameter' => $value]
     * @param string $idMethod id of the method to call, it's 'update' by default
     */
    public function update($object, ?array $parameter = null, string $idMethod = 'update'): void
    {
        try {
            $this->className = get_class($object);
            $parameter[$this->getOnlyClassName($this->className)] = $object;
            $this->parameter = $parameter;
            $this->cursorXML = $this->className;
            $this->config = $this->file[$this->className];
        } catch (\Throwable $th) {}

        $this->internalMethod = 'update';
        $this->method = $idMethod;
        $this->object = $object;

    }


    /**
     * Initialize an transaction delete
     *
     * @param object $object Object target
     * @param array|null $parameter Parameters for request, must be an array with ['parameter' => $value]
     * @param string $idMethod id of the method to call, it's 'delete' by default
     */
    public function delete(object $object, ?array $parameter = null, string $idMethod = 'delete'): void
    {
        try {

            $this->className = get_class($object);
            $parameter[$this->getOnlyClassName($this->className)] = $object;
            $this->parameter = $parameter;
            $this->cursorXML = $this->className;
            $this->config = $this->file[$this->className];
        } catch (\Throwable $th) {}

        $this->internalMethod = 'delete';
        $this->method = $idMethod;
        $this->object = $object;

    }


    /**
     * Launch transaction
     *
     * @return mixed
     */
    public function execute()
    {

        if(!$this->revalideCache()){
            return $this->loadResolver();
        }elseif ($this->getCache()) {


            $className = str_replace('=', '', base64_encode($this->className . "::" . $this->method));
            $parameter = array($this->IPDOFactory, $this->container);

            if(isset($this->parameter)){

                foreach ($this->parameter as $column => $value){
                    $parameter[] = $value;

                }
            }

            $cache = new $className();

            return call_user_func_array([$cache, 'execute'], $parameter);


        } else {
            return $this->loadResolver();
        }

    }


    /**
     * Take file cache to this class::request
     *
     * @return bool
     */
    private function getCache(): ?bool
    {
        $filename = self::STORAGE_CACHE . str_replace('=', '', base64_encode($this->className . '::' . $this->method)) . '.php';

        if(!file_exists($filename)){
            return false;
        }

        try {

            require_once $filename;

            return true;
        } catch (\Throwable $th) {

            if(strpos($th->getMessage(), 'No such file or directory') === false){
                throw new WorkException('PersistsManager encountered an error: when we try to take file ' . $filename . '.php , we have received this message: "' . $th->getMessage() . ' in line ' . $th->getLine() . '"');
            }
            return false;
        }
    }


    /**
     * Control that configuration hasn't change
     *
     * @param $class
     * @return bool
     */
    private function revalideCache(): bool
    {
        $lastConfig = self::STORAGE_CACHE . base64_encode($this->className . '::' . $this->method . 'Config') . '.php';

        try{

            $lastConfig = require $lastConfig;
            $lastConfig = unserialize($lastConfig);
        }catch (\Throwable $th){
            if(strpos($th->getMessage(), 'No such file or directory') === false){
                throw new WorkException('PersistsManager encountered an error: when we try to take file ' . self::STORAGE_CACHE . base64_encode($this->className . '::' . $this->method . 'Config') . '.php , we have received this message: "' . $th->getMessage() . ' in line ' . $th->getLine() . '"');
            }

            return false;
        }

        if (!empty(array_diff_assoc($lastConfig[$this->method], $this->config[$this->method]))) {

            unlink(self::STORAGE_CACHE . base64_encode($this->className . '::' . $this->method . 'Config') . '.php');
            return false;
        } else {
            return true;
        }
    }


    private function loadResolver()
    {

        $this->cache = unserialize($this->loadCache($this->cursorXML));

        $resolver = null;

        //Search class to instanciate
        if($this->internalMethod === 'read') {
            $resolver = $this->container->get(ResolverSelect::class);
            $resolver->className = $this->className;
        }elseif($this->internalMethod === 'update'){
            $resolver = $this->container->get(ResolverUpdate::class);
        }elseif($this->internalMethod === 'create'){
            $resolver = $this->container->get(ResolverCreate::class);
        }elseif($this->internalMethod === 'delete'){
            $resolver = $this->container->get(ResolverDelete::class);
        }

        //Map property for target class
        foreach($this->cache[$this->className]['property'] as $property){
            $resolver->mapping(
                $property['column'],
                $property['accessor'],
                $property['mutator'],
                $property['type'],
                $property['scope'],
                $this->cache[$this->className]['table'],
                $this->cache[$this->className]['keyArray']
            );
        }

        //Map the dependency for target class
        if(!empty($this->cache[$this->className]['dependency'])){
            foreach($this->cache[$this->className]['dependency'] as $classNameDependency => $dependency){

                $this->cahe = $this->loadCache($classNameDependency);

                //If cache for dependency doesn't exists, rebuild
                if(array_key_exists($classNameDependency, $this->cache)) {
                    foreach ($this->cache[$classNameDependency]['property'] as $property) {
                        $resolver->dependency[$classNameDependency][$property['column']] = [
                            'column' => $property['column'],
                            'mutator' => $property['mutator'],
                            'type' => $property['type'],
                            'scope' => $property['scope'],
                            'table' => $this->cache[$classNameDependency]['table'],
                            'keyArray' => $this->cache[$classNameDependency]['keyArray']
                        ];
                    }
                }else{
                    $this->loadCache($classNameDependency);
                }
            }
        }

        $resolver->method = $this->method;
        $resolver->cache = $this->cache;
        $resolver->instance = $this->IPDOFactory;
        $resolver->className = $this->className;
        $resolver->parameter = $this->parameter;

        if(isset($this->config)) {
            $resolver->config = $this->config;
        }else{
            throw new WorkException('PersistsManager encountered an error: configuration not found for ' . $this->className . ' in persists manager configuration (' . str_replace(ROOT, '', self::STORAGE_CONFIGURATION) . '), <br> please, make an configuration or verify your syntax');
        }
        $resolver->request = $this->config[$this->method]['request'];

        if(isset($this->object)){
            $resolver->object = $this->object;
        }

        if(isset($this->config[$this->method]['return'])){
            $resolver->willReturn = $this->config[$this->method]['return'];
        }

        if(isset($this->config[$this->method]['id_config_database'])){
            $resolver->idConfig = $this->config[$this->method]['id_config_database'];
        }elseif(isset($this->config['id_config_database'])){
            $resolver->idConfig = $this->config['id_config_database'];
        }else{
            $resolver->idConfig = 'default';
        }

        $resolver->execute();
        $this->loop++;

        if($this->loop > 2){
            throw new WorkException('PersistsManager encountered an error: Unknow error');
        }

        return $this->execute();

    }


    /**
     * Build or rebuild cache and return new cache
     *
     * @return \SimpleXMLElement
     * @throws \NoMess\Exception\WorkException
     */
    private function loadCache($className) : string
    {
        $builder = $this->container->get(BuilderPersistsManager::class);
        $builder->build($className);

        return require self::STORAGE_CACHE . '/persistsmanager.php';
    }


    /**
     * Return only className
     *
     * @param string $fullclassName
     * @return string
     */
    protected function getOnlyClassName(string $fullclassName): string
    {

        $tmp = explode('\\', $fullclassName);

        return $tmp[count($tmp) - 1];
    }
}