<?php


class RegisterObject{

    /**
     * Enregistre l'entité dans l'application
     *
     * @param array $property
     * @param string $name
     * @param string $pathRegister
     * @return void
     */
    public function register(array $property, string $name, string $pathRegister) : void
    {
        $content = $this->assembler($property, $name, $pathRegister);

        if(!file_put_contents($pathRegister . $name . ".php", $content)){
            echo "Echec: Impossible de créer " . $name . ".php\n";
            echo "Lancement du mode sans echec...\n";
            sleep(1);

            if(!@file_put_contents('../App/var/mse/installer/' . $name . ".php", $content)){
                echo "Echec: Le mode sans echec n'a pas pu enregistrer l'entité\n";
                echo "Copier/Coller l'entité: \n";
                echo $content;
            }
        }else{
            echo "Création de " . $pathRegister . $name . ".php reussie\n";
            sleep(1);
        }
    }

    /**
     * Assemble tous les élément de l'entité + entête
     *
     * @param array $property
     * @param string $name
     * @param string $pathRegister
     * @return string
     */
    private function assembler(array $property, string $name, string $pathRegister) : string
    {
        $entity = "<?php\n\n" . $this->defineNamespace($pathRegister) . "\n\n\nclass " . $name . " extends EntityManager\n{ \n\n";
        $entity = $entity . $this->addProperty($property) . "\n\n\n" . $this->addGs($property) . "}";

        return $entity;
    }

    /**
     * Génère le namespace
     *
     * @param string $pathRegister
     * @return string
     */
    private function defineNamespace(string $pathRegister) : string
    {
        $tabSpace = explode('/', $pathRegister);
        $namespace = 'namespace App';

        $i = 0;
        $addSpace = false;

        for($i = 0; $i < count($tabSpace); $i++){
            if(!empty(trim($tabSpace[$i]))){
                if($tabSpace[$i] === 'Modules'){
                    $addSpace = true;
                }

                if($addSpace === true){
                    $namespace = $namespace . '\\' . $tabSpace[$i];
                }
            }
        }

        return $namespace . ";\n\nuse NoMess\\Core\\EntityManager;";
    }

    /**
     * Génération des propriété
     *
     * @param array $property
     * @return string
     */
    private function addProperty(array $property) : string
    {
        $blocProperty = null;

        foreach($property as $value){
            $blocProperty = $blocProperty . "\t/**\n\t *\n\t * @var " . $value->getTypePhp() . "\n\t */\n\tprivate $" . $value->getName() . ";\n\n";
        }

        return $blocProperty;
    }

    /**
     * Ajoute les getters et setters
     *
     * @param array $property
     * @return string
     */
    private function addGs(array $property) : string
    {
        $gs = null;

        foreach($property as $value){
            $gs = $gs . $value->getGetter() . "\n\n" . $value->getSetter() . "\n\n";
        }

        return $gs;
    }
}