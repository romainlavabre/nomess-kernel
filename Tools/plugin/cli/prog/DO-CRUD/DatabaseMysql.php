<?php

use NoMess\Core\PDOFactory;

class DatabaseMysql {

    const IPDOFACTORY = '../App/vendor/NoMess/IPDOFactory.php';

    /**
     * Nom de la table
     *
     * @var string
     */ 
    private $nameTable;

    /**
     * Tableau des propriété contenent les ligne sql
     *
     * @var array
     */
    private $property;

    /**
     * Chemin d'accès au fichier de configuration de pdofactoru
     *
     * @var string
     */
    private $pathConfig;

    /**
     * Chemin de la class PDOFactory
     *
     * @var string
     */
    private $pathPdoFactory;

    /**
     *
     * @param string $nameTable
     * @param array $property
     * @param string $pathConfig
     * @param string $pathPdoFactory
     */
    public function __construct(string $nameTable, array $property, string $pathConfig, string $pathPdoFactory)
    {
        $this->nameTable = $nameTable;
        $this->property = $property;
        $this->pathConfig = $pathConfig;
        $this->pathPdoFactory = $pathPdoFactory;

        $db  = $this->getConnexion();
        $req = $this->getRequest();

        if(!createTable($req, $db)){
            $this->lost();
        }
    }

    /**
     * Créer la requete
     *
     * @return string
     */
    private function getRequest() : string
    {
        $req = "CREATE TABLE " . $this->nameTable . " (\n" . $this->property[0]->getSql();

        for($i = 0; $i < count($this->property); $i++){
            if($i > 0){
                $req = $req . ",\n" . $this->property[$i]->getSql();
            }
        }

        $req = $req . "\n)";

        return $req;
    }

    /**
     * Retourne une instance de PDO ou crash
     *
     * @return \PDO
     */
    private function getConnexion() : \PDO
    {
        require self::IPDOFACTORY;
        require $this->pathConfig;
        require $this->pathPdoFactory;

        echo "Teste: Connexion avec la base de données...\n";

        $pdo = new PDOFactory();

        if(@$db = $pdo->getConnection()){
            echo "Connexion réussie\n";
            return $db;
        }else{
            echo "Echec de la connexion\n";

            do{
                $retry = false;

                $host = rdl("Host: ");
                $dbname = rdl("dbname: ");
                $user = rdl("User: ");
                $password = rdl("Password: ");

                if(!@$db = new \PDO('mysql:host=' . $host . ';dbname=' . $dbname . '', $user, $password, array(
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'))){
                        echo "Echec de la connexion\n";
                        
                        $response = rdl("Réessayer ? [O/Enter]");

                        if(!is_null($response)){
                            $retry = true;
                        }else{
                            echo $this->lost();

                            die();
                        }
                    
                }else{
                    return $db;
                }
            }while($retry);

        }
    }

    /**
     * Gere proprement l'abandont de la connexion (crash)
     *
     * @return void
     */
    private function lost() : void
    {
        echo "Abandont...\n";
        sleep(1);

        echo "\n\n\n" . $this->getRequest() . "\n\n\n";
        sleep(2);

        echo "Erreur: La table n'a pas pu être créé\n";
        die();
    }


}