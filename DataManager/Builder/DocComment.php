<?php
namespace NoMess\DataManager\Builder;

use NoMess\Exception\WorkException;

class DocComment
{

    /**
     * Nom de la class
     *
     * @var string
     */
    private $className; 

    /**
     * Clé en session (unique)
     *
     * @var string
     */
    private $key;


    /**
     * Clé du tableau en session
     *
     * @var string
     */
    private $keyArray;


    /**
     * dependence de session
     *
     * @var array
     */
    private $sesDepend = array();

    /**
     * table de l'objet
     *
     * @var string
     */
    private $base;

    /**
     * Insertion avec le retrour de la  mise en base
     *
     * @var string
     */
    private $insert;

    /**
     * Dépendance de la base de donnée
     *
     * @var array
     */
    private $dbDepend = array();

    /**
     * Contient les noInsert, noUpdate, noDelete
     *
     * @var array
     */
    private $noTransaction = array();


    /**
     * Contient les alias de method
     *
     * @var array
     */
    private $alias;



    /**
     *
     * @param string $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
    }


    /**
     *
     * @return string
     */
    public function getClassName() : string
    {
        return $this->className;
    }

    /**
     *
     * @param string $setter
     * @return void
     */
    public function setKey(string $setter) : void
    {
        if($this->key === null){
            $this->key = $setter;
        }else{
            throw new WorkException('Pour la class ' . $this->className . ', une seule clé de session est attendu');
        }
    }

    /** 
     *
     * @return string|null
     */
    public function getKey() : ?string
    {
        return $this->key;
    }
    
    /**
     *
     * @param string $setter
     * @return void
     */
    public function setKeyArray(string $setter) : void
    {
        if($this->keyArray === null){
            $this->keyArray = $setter;
        }else{
            throw new WorkException('Pour la class ' . $this->className . ', une seule clé de tableau est attendu');
        }
    }

    /**
     *
     * @return string|null
     */
    public function getKeyArray() : ?string
    {
        return $this->keyArray;
    }

    /**
     *
     * @param string $getter
     * @param string $setter
     * @return void
     */
    public function setSesDepend(string $getter, string $setter) : void
    {
        $this->sesDepend[$getter] = $setter;
    }

    /**
     *
     * @return array|null
     */
    public function getSesDepend() : ?array
    {
        return $this->sesDepend;
    }

    /**
     *
     * @param string $setter
     * @return void
     */
    public function setBase(string $setter) : void
    {
        if($this->base === null){
            $this->base = $setter;
        }else{
            throw new WorkException('Pour la class ' . $this->className . ', une seul table est attendu');
        }
    }

    /**
     *
     * @return string|null
     */
    public function getBase() : ?string
    {
        return $this->base;
    }

    /**
     *
     * @param string $getter
     * @param string $setter
     * @return void
     */
    public function setInsert(string $setter) : void
    {
        $this->insert = $setter;
    }

    /**
     *
     * @return string|null
     */
    public function getInsert() : ?string
    {
        return $this->insert;
    }

    /**
     *
     * @param string $getter
     * @param string $setter
     * @return void
     */
    public function setDbDepend(string $getter, string $setter) : void
    {
        $this->dbDepend[$getter] =  $setter;
    }

    public function getDbDepend() : ?array
    {
        return $this->dbDepend;
    }

    public function getNoTransaction() : ?array
    {
        return $this->noTransaction;
    }

    public function setNoTransaction(?array $setter) : void
    {
        if(!empty($setter)){
            $this->noTransaction = $setter;
        }
    }

    public function setAlias(string $key, string $value) : void
    {
        $this->alias[$key] = $value;
    }

    public function getAlias() : ?array
    {
        return $this->alias;
    }

    public function __destruct(){}

}