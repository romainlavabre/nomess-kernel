<?php


class Crud{

    /**
     * Constante de \PDO
     *
     * @var array
     */
    private $param = array(
        'bool' => "\\PDO::PARAM_BOOL",
        'int' => "\\PDO::PARAM_INT",
        'float' => "\\PDO::PARAM_STR",
        'double' => "\\PDO::PARAM_STR",
        'string' => "\\PDO::PARAM_STR"
    );


    /**
     * Enregistre le fichier avec son contenu
     *
     * @param array $property // Tableau des propriétés de l'objet
     * @param string $name //nom de l'objet
     * @param string $dbname //nom de la table
     * @return void
     */
    public function register(array $property, string $name, string $dbname, string $pathRegister) : void
    {
        $content = $this->assembler($property, $name, $dbname);

        if(!@file_put_contents($pathRegister . $name . "Table.php", $content)){
            echo "Echec: Impossible de créer " . $name . "Table.php\n";
            echo "Lancement du mode sans echec...\n";
            sleep(1);

            if(!@file_put_contents('../../var/mse/installer/' . $name . "Table.php", $content)){
                echo "Echec: Le mode sans echec n'a pas pu enregistrer l'entité\n";
                echo "Copier/Coller l'entité: \n";
                echo $content;
            }
        }else{
            echo "Création de " . $pathRegister . $name . "Table.php reussie\n";
            sleep(1);
        }
    }

    /**
     * Assemble l'entité
     *
     * @param array $property
     * @param string $name
     * @param string $dbname
     * @return string
     */
    private function assembler(array $property, string $name, string $dbname) : string
    {
        $content = "<?php\n" . $this->getNamespace($name) . "\n\n\n class " . $name . "Table extends AppManager{\n\n\n\t/**\n\t* Instance de PDO\n\t*\n\t* @var \PDO\n\t*/\n\tprivate \$database;\n\n\t/**\n\t * \n\t * @Inject\n\t *\n\t * @var IPDOFactory \$db\n\t */\n\tpublic function __construct(IPDOFactory \$db)\n\t{
            \n\t\t\$this->database = \$db->getConnection();\n\t}\n\n\t" . $this->getRead($property, $dbname, $name) ."\n\n\n\t" . $this->getCreate($property, $dbname, $name) . "\n\n\n\t" . $this->getUpdate($property, $dbname, $name) . "\n\n\n\t" . $this->getDelete($property, $dbname, $name)
            . "\n}";

        return $content;
    }

    /**
     * Retourne le namespace
     *
     * @return string
     */
    private function getNamespace(string $name) : string
    {
        return "namespace App\\Tables;\n\nuse NoMess\\Core\\AppManager;\nuse App\\Modules\\" . $name . "\\Entity\\" . $name . ";\nuse NoMess\Core\IPDOFactory;";
    }

    /**
     * Retourne la requete
     *
     * @param int $type //0 = read, 1 = create, 2 = update, 3 = delete
     * @param array $property //tableau de propriété
     * @param string $dbname //nom de la table
     * @param string $name //nom de l'entité
     * @return string
     */
    private function getRequete(int $type, array $property, string $dbname, string $name) : string
    {
        if($type === 0){//read ---------------------------------------------------
            $req = "'SELECT * FROM " . $dbname . "'";
            $prepare = "\$req = \$this->database->prepare(" . $req . ");";
            $exec = "\$req->execute();";

            return $prepare . "\n\t\t" . $exec;

        }else if($type === 1){//create --------------------------------------------
            $req = "'INSERT INTO " . $dbname . "(" . $property[1]->getName();

            $i = 0;

            foreach($property as $value){
                if($i > 1){
                    $req = $req . ", " . $value->getName();
                }else{
                    $i++;
                }
            }

            $req = $req . ") VALUES (:" . $property[1]->getName();
            
            $i = 0;

            foreach($property as $value){
                if($i > 1){
                    $req = $req . ", :" . $value->getName();
                }else{
                    $i++;
                }
            }

            $req = $req . ")'";

            $prepare = "\$req = \$this->database->prepare(" . $req . ");\n";

            $i = 0;

            foreach($property as $value){

                if($i > 0){
                    $param = null;

                    foreach($this->param as $key => $pdoParam){
                        if($key === $value->getTypePhp()){
                            $param = $pdoParam;
                        }
                    }

                    if($param !== null){
                        $prepare = $prepare . "\t\t\$req->bindValue(':" . $value->getName() . "', $" . lcfirst($name) . "->get" . ucfirst($value->getName()) . "(), " . $param . ");\n";
                    }else{
                        $prepare = $prepare . "\t\t\$req->bindValue(':" . $value->getName() . "', $" . lcfirst($name) . "->get" . ucfirst($value->getName()) . "());\n";
                    }
                }
            }

            $exec = "\t\t\$req->execute();";

            return $prepare . $exec;
        }else if($type === 2){ //update ----------------------------------------------
            $req = "'UPDATE " . $dbname . " SET " . $property[1]->getName() . " = :" . $property[1]->getName();

            $i = 0;

            foreach($property as $value){
                if($i > 1){
                    $req = $req . ", " . $value->getName(). " = :" . $value->getName();
                }else{
                    $i++;
                }
            }

            $req = $req . " WHERE id = :id'";

            $prepare = "\$req = \$this->database->prepare(" . $req . ");\n";

            foreach($property as $value){
                $param = null;

                foreach($this->param as $key => $pdoParam){
                    if($key === $value->getTypePhp()){
                        $param = $pdoParam;
                    }
                }

                if($param !== null){
                    $prepare = $prepare . "\t\t\$req->bindValue(':" . $value->getName() . "', $" . lcfirst($name) . "->get" . ucfirst($value->getName()) . "(), " . $param . ");\n";
                }else{
                    $prepare = $prepare . "\t\t\$req->bindValue(':" . $value->getName() . "', $" . lcfirst($name) . "->get" . ucfirst($value->getName()) . "());\n";
                }
            }

            $exec = "\t\t\$req->execute();";

            return $prepare . $exec;
        }else{//delete--------------------------------------------------------------------------
            $req = "'DELETE FROM " . $dbname . " WHERE id = :id'";
            $prepare = "\$req = \$this->database->prepare(" . $req . ");\n";
            $prepare = $prepare . "\t\t\$req->bindValue(':id', $" . lcfirst($name) . "->getId(), \PDO::PARAM_INT);\n";
            $exec = "\t\t\$req->execute();";

            return $prepare . $exec;
        }
    }
    

    /**
     * Retourne la function read
     *
     * @param array $property
     * @param string $dbname
     * @param string $name
     * @return string
     */
    private function getRead(array $property, string $dbname, string $name) : string
    {

        return "/**\n\t * Récupère les données en base\n\t *\n\t * @return array|null\n\t */\n\tpublic function read() : ?array\n\t{\n\t\t" . $this->getRequete(0, $property, $dbname, $name) . "\n\n\t\t\$tab = array();\n\n\t\twhile(\$donnee = \$req->fetch(\PDO::FETCH_ASSOC)){\n\t\t\t\$entity = new " . $name . "();\n\t\t\t\$entity->hydrate(\$donnee);\n\n\t\t\t\$tab[\$entity->getId()] = \$entity;\n\t\t}\n\n\t\treturn \$tab;\n\t}";
    }

    /**
     * Retourne la function create
     *
     * @return string
     */
    private function getCreate(array $property, string $dbname, string $name) : string
    {
        return "/**\n\t * Insert les données en base\n\t *\n\t * @param " . $name ." \$" . lcfirst($name) . "\n\t * @return array|null\n\t */\n\tpublic function create(" . ucfirst($name) . " \$" . lcfirst($name) . ") : void\n\t{\n\t\t" . $this->getRequete(1, $property, $dbname, $name) . "\n\n\t}";
    }

    /**
     * Retourne la function Update
     *
     * @return string
     */
    private function getUpdate(array $property, string $dbname, string $name) : string
    {
        return "/**\n\t * MAJ les données en base\n\t * \n\t * @param " . $name ." \$" . lcfirst($name) . "\n\t * @return array|null\n\t */\n\tpublic function update(" . ucfirst($name) . " \$" . lcfirst($name) . ") : void\n\t{\n\t\t" . $this->getRequete(2, $property, $dbname, $name) . "\n\n\t}";
    }

    /**
     * Retourne la function delete
     *
     * @return string
     */
    private function getDelete(array $property, string $dbname, string $name) : string
    {
        return "/**\n\t * Supprime les données en base\n\t * \n\t * @param " . $name ." \$" . lcfirst($name) . "\n\t * @return array|null\n\t */\n\tpublic function delete(" . ucfirst($name) . " \$" . lcfirst($name) . ") : void\n\t{\n\t\t" . $this->getRequete(3, $property, $dbname, $name) . "\n\n\t}";
    }
}