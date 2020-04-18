<?php

require 'Control.php';
require 'Crud.php';
require 'DatabaseMysql.php';
require 'Property.php';
require 'RegisterObject.php';


class Main{
    /**
     * Chemin vers les modules
     *
     * @var string
     */
    private $pathModule = '../App/src/Modules/';

    /**
     * Chamin vers le dossier des tables
     *
     * @var string
     */
    private $pathTable = '../App/src/Tables/';

    /**
     * Dossier de config
     *
     * @var string
     */
    private $pathConfigPdo = '../App/config/config-dev.php';

    /**
     * Chemin vers la classe PDOFactory
     *
     * @var string
     */
    private $pathPdoFactory = '../vendor/nomess/kernel/Database/PDOFactory.php';

    /**
     * Nom de la table en base
     *
     * @var string
     */
    private $nameTable;

    /**
     * L'objet
     * Nom
     * Propriété
     *
     * @var string
     */
    private $name;

    /**
     * Propriété de l'objet et ligne Sql        
     *
     * @var array
     */
    private $property = array();


    /**
     * Recupération des données et aiguillage
     *
     * @return void
     */
    public function installer()
    {

        $pathRegister = null;

        // Context
        $response = rdl("Package [vide: auto ou Modules/.../]: ");

        if(!is_null($response)){
            $pathRegister = $this->pathModule . $response;
        }

        unset($response);

        do{
            $response = rdl("Nom de l'entité: ");
            $this->name = ucfirst($response);
        }while(is_null($response));

        $property = new Property();
        $property->setName('id');
        $property->setTypePhp('int');
        $property->setTypeSql('INT');
        $property->setTaille('10');
        $property->setTabOther('UNSIGNED');
        $property->setTabOther('AUTO_INCREMENT');
        $property->setTabOther('PRIMARY KEY');

        $this->property[] = $property;

        unset($property);
        unset($response);

        echo "L'id est déjà intégré, lancement de la création des propriétés...\n";
        sleep(0.5);

        do{
            $property = new Property();
            $new = true;

            //Nom de la propriété -----------------------------------
            do{
                $response = rdl("Nom: ");

                if(!is_null($response)){
                    $property->setName($response);
                }
            }while(is_null($response));

            //Type de la propriété -----------------------------------
            do{
                $retry = false;

                echo "bool\nint\nfloat\ndouble\nstring\narray\nobject\ncallable\niterable\nresource\nnull\n";

                $response = rdl("Type (php): ");

                if(!is_null($response)){
                    if(!Control::controlType($response)){
                        echo "Le type saisie est incorrect\n";

                        $retry = true;
                    }else{
                        $property->setTypePhp($response);
                    }

                }else{
                    $retry = true;
                }
            }while($retry === true);

            //type SQL de la propriété ---------------------------------
            do{
                $response = rdl("Type sql: ");

                if(!is_null($response)){
                    $property->setTypeSql(strtoupper($response));
                }
            }while(is_null($response));

            //Taille ----------------------------------------------------
            $response = rdl("Taille: ");

            if(!is_null($response)){
                $property->setTaille($response);
                unset($response);
            }

            //Autre valeur SQL -------------------------------------------
            do{

                if(!empty($property->getTabOther())){
                    echo "Actuellement:\n";

                    foreach($property->getTabOther() as $value){
                        echo $value ."\n";
                    }
                }

                $response = rdl("Valeur SQL suplémentaire: ");

                if(!is_null($response)){
                    $property->setTabOther(strtoupper($response));
                }
            }while(!is_null($response));

            $this->property[] = $property;

            do{
                $response = rdl("Continuer ? O/N");
                
                if(strtolower($response) === "n"){
                    $new = false;
                }
            }while(is_null($response));

            
        }while($new === true);


        //Affichage des résultats

        echo "Votre Entité:\n";

        $i = 1;

        echo ucfirst($this->name) . "\n";

        foreach($this->property as $value){
            echo $i . ". " . $value->getName() . " : " . $value->getTypePhp() . "\n";
            $sql = strtoupper($value->getTypeSql());

            if($value->getTaille()){
                $sql = $sql . "(" . $value->getTaille() . ") ";
            }else{
                $sql = $sql . " ";
            }

            foreach($value->getTabOther() as $tab){
                $sql = $sql . strtoupper($tab) . " ";
            }

            $sql = $sql . "\n\n";

            echo $sql;

            $i++;
        }

        sleep(3);

        echo "Lancement de l'enregistrement de l'objet...\n";
        

        if($pathRegister === null){
            @mkdir($this->pathModule . $this->name . "/Entity", 0777, true);

            $pathRegister = $this->pathModule . $this->name . "/Entity/";
        }

        $register = new RegisterObject();
        $register->register($this->property, $this->name, $pathRegister);


        do{
            $response = rdl("Quelle sera le nom de votre table ?");

            $this->nameTable = $response;
        }while(is_null($response));

        echo "Lancement de l'enregistrement de l'entité...\n";
        sleep(1);

        $crud = new Crud();
        $crud->register($this->property, $this->name, $this->nameTable, $this->pathTable);

        echo "Lancement de la création de la table...\n";
        
        new DatabaseMysql($this->nameTable, $this->property, $this->pathConfigPdo, $this->pathPdoFactory);

    }
}