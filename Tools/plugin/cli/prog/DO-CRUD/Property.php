<?php


class Property{

    /**
     * Nom de la propriété
     *
     * @var string
     */
    private $name;

    /**
     * Typage php
     *
     * @var string
     */
    private $typePhp;

    /**
     * Typage db
     *
     * @var string
     */
    private $typeSql;

    /**
     * Taille
     *
     * @var string
     */
    private $taille;

    /**
     * Valeur Db
     *
     * @var array
     */
    private $tabOther = array();

    /**
     * Créer le getter
     *
     * @return string
     */
    public function getGetter() : string
    {
        return "\t/**\n\t * @return " . $this->typePhp . "\n\t */\n\tpublic function get" . ucfirst($this->name) . "() : " . $this->typePhp . "\n\t{\n\t\treturn \$this->" . $this->name . ";\n\t}";
    }

    /**
     * Créer le setter
     *
     * @return string
     */
    public function getSetter() : string
    {
        return "\t/**\n\t * @param " . $this->typePhp . " $" . $this->name . "\n\t * @return void\n\t */\n\tpublic function set" . ucfirst($this->name) . "(" . $this->typePhp . " \$setter) : void\n\t{\n\t\t\$this->" . $this->name . " = \$setter;\n\t}";
    }

    /**
     * Créer la ligne sql
     *
     * @return string
     */
    public function getSql() : string
    {
        $sql = $this->name . " " . $this->typeSql;
        
        if(!empty($this->taille)){
            $sql = $sql . "(" . $this->taille . ") ";
        }else{
            $sql = $sql . " ";
        }

        foreach($this->tabOther as $value){
            $sql = $sql . " " . $value;
        }

        return $sql;
    }

    //========================== GETTERS ====================================
 
    public function getName() : ?string
    {
        return $this->name;
    }

    public function getTypePhp() : ?string
    {
        return $this->typePhp;
    }

    public function getTypeSql() : ?string
    {
        return $this->typeSql;
    }

    public function getTaille() : ?string
    {
        return $this->taille;
    }

    public function getTabOther() : ?array
    {
        return $this->tabOther;
    }

    //=========================== SETTERS ====================================
    public function setName(string $setter) : void
    {
        $this->name = $setter;
    }

    public function setTypePhp(string $setter) : void
    {
        $this->typePhp = $setter;
    }

    public function setTypeSql(string $setter) : void
    {
        $this->typeSql = $setter;
    }

    public function setTaille(string $setter) : void
    {
        $this->taille = $setter;
    }

    public function setTabOther(string $setter) : void
    {
        $this->tabOther[] = $setter;
    }

}