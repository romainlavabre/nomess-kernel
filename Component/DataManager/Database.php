<?php

namespace NoMess\Component\DataManager;

use DI\Annotation\Inject;
use NoMess\Component\Component;
use NoMess\Component\DataManager\Builder\BuilderDataManager;
use NoMess\Container\Container;
use NoMess\Database\IPDOFactory;
use NoMess\ObserverInterface;

/**
 * Class Database
 * @package NoMess\Component\DataManager
 */
class Database extends Component
{

    private const CACHE_DATA_MANAGER        = ROOT . 'App/var/cache/dm/datamanager.xml';

    private const CREATE                    = 'create:';
    private const UPDATE                    = 'update:';
    private const DELETE                    = 'delete:';


    private array $data;
    private int $cursor = -1;

    private string $idConfigDatabase = 'default';


    private DataManager $datamanager;

    private Container $container;



    /**
     * @Inject()
     *
     * Database constructor.
     * @param DataManager $dataManager
     * @param Container $container
     * @throws \NoMess\Exception\WorkException
     */
    public function __construct(DataManager $dataManager,
                                Container $container)
    {

        $this->datamanager = $dataManager;
        $this->container = $container;
        parent::__construct();
        $this->controlCache();
    }


    /**
     * ### Push a  ***creation*** of the target object in pile
     *
     *
     * @param array $param Object Array, by default, target is the first object found by DataManager in the parameters,<br>
     * If, for some reason, you can't optimize postion of object in the parameters, you can explicitly specify the type
     * that DataManager should search. (see $type option for more).
     * <br><br>
     * @param string $type if you can't fulfill a condition of $param, specify an object type that DataManager should search.
     *
     * @return Database
     *
     */
    public function create(array $param, string $type = null): Database
    {
        $this->instanceData([self::CREATE . $type => $param]);
        return $this;
    }


    /**
     * ### Push a  ***update*** of the target object in pile
     *
     * @param array $param
     * @param string|null $type
     * @return $this
     */
    public function update(array $param, string $type = null): Database
    {

        $this->instanceData([self::UPDATE . $type => $param]);

        return $this;
    }


    /**
     * ### Push a  ***delete*** of the target object in pile
     *
     * @param array $param
     * @param array|null $dependancy
     * @param array|null $runtimeConfig
     * @param string|null $type
     * @return $this
     */
    public function delete(array $param, string $type = null): Database
    {

        $this->instanceData([self::DELETE . $type => $param]);

        return $this;
    }


    /**
     *
     * Push an arbitrary method in pile
     *
     * It allow execute an transaction for alias method to "C.R.U.D."
     *
     * WARNING if you use update or delete method:
     * If you work with session, for guarentee a consistency data, pass always a copy of object and not by reference,
     * the original object that find inside session scope will be overwrite by new value (provided the keyArray are identical)
     *
     * @param string $method Name of method
     *
     * @return Database
     *
     */
    public function aliasMethod(string $method, array $param, string $type = null): Database
    {

        $this->instanceData([$method . ':' . $type => $param]);

        return $this;

    }



    /**
     * @param array $configuration he runtime configuration allow temporarly disable once (or multiple) configuration or add any more.
     * Possible parameters:<br><br>
     * Depend:<br>
     * &nbsp&nbsp&nbsp&nbsp - false : disable an dependency<br>
     * &nbsp&nbsp&nbsp&nbsp - Full\Quanlified\class::methodName : Use specified class::method to inject value<br>
     * &nbsp&nbsp&nbsp&nbsp - Mixed value : All arbitrary value, if is an array, DataManager will attempt insertion as a block, then if an error has occured it will iterate your array<br>
     * &nbsp&nbsp&nbsp&nbsp - Array object : This parameter is an extention of full name of class, because, the data taken is defer, but it more, you can pass an array data for unique setter method, consequently, you must pass an reference of object dependency
     *      if you need defer method and pass an array data, this is option is good choice
     * Transaction:<br>
     * &nbsp&nbsp&nbsp&nbsp - false : Disables an transaction for this encapsed object<br>
     * &nbsp&nbsp&nbsp&nbsp - true : Enables an transaction for this encapsed object<br>
     * Insert:<br>
     * &nbsp&nbsp&nbsp&nbsp - false : Insertion disabled<br>
     * &nbsp&nbsp&nbsp&nbsp - nomess_backTransaction : The inserted value is returned by transaction<br>
     * &nbsp&nbsp&nbsp&nbsp - Mixded value : All arbitrary value, if is an array, DataManager will attempt insertion as a block, then if an error has occured it will iterate your array<br>
     * &nbsp&nbsp&nbsp&nbsp - Array object : This parameter is an extention of full name of class, because, the data taken is defer, but it more, you can pass an array data for unique setter method, consequently, you must pass an reference of object dependency
     *      if you need defer method and pass an array data, this is option is good choice
     * <br><br>
     * Format<br>
     *
     * ['depend' => [<br>
     *          &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 'setterMethod' => false    |     string('Full\Quanlified\class::methodName')    |   mixed value     | ['getter' => ObjectReference|array(ObjectReference) [, ...]],<br>
     *          &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp '...'<br>
     *          &nbsp&nbsp&nbsp&nbsp ],<br>
     * 'transation' => [<br>
     *          &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 'setterMethod' => true/false,<br>
     *          &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp '...'<br>
     *        &nbsp&nbsp&nbsp&nbsp ],<br>
     * 'insert' => [<br>
     *          &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 'setterMethod' => false |   'nomess_backTransaction'     |    mixed value    |   ['getter' => ObjectReference|array(ObjectReference) [, ...]],<br>
     *          &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp '...'<br>
     *      &nbsp&nbsp&nbsp&nbsp ]<br>
     * ]<br>
     * @return Database
     */
    public function setConfiguration(array $configuration): Database
    {
        $this->data[$this->cursor]['runtimeConfig'] = $configuration;
    }


    /**
     * @param array $dependency By deflaut, the dependance of target object will search in object group that has incur a
     * treatment only in the current request.<br>
     * If a object of identical type exists in group, only first will be keep.<br>
     * For modified this behaviour, you should pass by reference the dependency of target object.
     *
     * @return Database
     */
    public function setDependency(array $dependency): Database
    {

        $this->data[$this->cursor]['depend'] = $dependency;
    }



    /**
     * Return a builder for persists manager class
     *
     * @param string|null $className
     * @param array|null $parameter
     * @param string|null $idMethod
     * @return $this
     */
    public function buildPM(?string $className = null, ?array $parameter = null, ?string $idMethod = null): Database
    {


        if($className !== null && ($parameter !== null || $idMethod !== null)) {
            $this->data[$this->cursor]['persistsManager'][$className] = [
                'parameters' => $parameter,
                'idMethod' => $idMethod
            ];
        }elseif(!isset($this->data[$this->cursor]['persistsManager'])){
            $this->data[$this->cursor]['persistsManager'] = true;
        }

        return $this;
    }


    /**
     * Modify pointer of configuration database
     *
     * @param string $idConfigurationDatabase
     */
    public function setDatabaseConfiguration(string $idConfigurationDatabase): void
    {

        $this->idConfigDatabase = $idConfigurationDatabase;

    }


    /**
     * Launch transaction process, by running request in pile, if an error occured, return false, else return true
     *
     * @return bool
     */
    public function manage(): bool
    {
        return $this->datamanager->database($this->data, $this->idConfigDatabase);
    }


    /**
     * Launch builder if cache file doesn't exists
     *
     * @throws \NoMess\Exception\WorkException
     */
    public function controlCache(): void
    {
        if(!file_exists(self::CACHE_DATA_MANAGER)){
            $buildMonitoring = $this->container->get(BuilderDataManager::class);
            $buildMonitoring->builderManager();
        }
    }


    /**
     * Create line of data to persists
     *
     * @param array $request
     */
    private function instanceData(array $request): void
    {
        $this->data[] = [
            'request' => $request,
            'depend' => null,
            'runtimeConfig' => null,
            'persistsManager' => null
        ];

        $this->cursor++;
    }

}