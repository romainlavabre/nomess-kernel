<?php


namespace NoMess\Component\PersistsManager\ResolverRequest;


use NoMess\Component\PersistsManager\Resolver;


class ResolverSelect extends Resolver
{


    /**
     * Contains the bring closer between table and suffix
     */
    private array $suffixTable;


    /**
     * Contains the last class where data insert is build
     */
    private ?string $internalCursor;

    /**
     * Contains class name
     */
    public ?string $className;


    /**
     * Executable
     * Contains data to insert
     * [column|alias => method]
     */
    private array $dataInsert;


    /**
     * Launcher
     */
    public function execute(): void
    {

        $this->mappeSuffixTable();
        $this->parseRequest();
        $this->buildCache();
        $this->registerInitialConfig($this->className);
        $this->purge();
    }


    /**
     * Specific to select request
     * Parse request for build class of persistence
     */
    private function parseRequest(): void
    {

        //purge keyword for keep only parameters
        $tmpOne = explode('FROM', $this->request);
        $tmpTwo = str_replace('SELECT', '', $tmpOne[0]);


        //If select all, do this, else search column and alias
        if (trim($tmpTwo) === '*') {
            foreach ($this->propertyMapping as $key => $value) {
                $this->setData[$value['column']] = null;
            }
        } else {
            $finally = explode(',', $tmpTwo);

            foreach ($finally as $value) {
                if (strpos($value, 'AS') !== null) {
                    $tabKeypair = explode('AS', $value);

                    $this->setData[trim($tabKeypair[0])] = trim($tabKeypair[1]);
                } else {
                    $this->setData[$value] = null;
                }
            }
        }


        $this->buildParameter();
        $this->selectGetLine();


    }


    /**
     * Specifique to select request
     * Parse request for build the setter
     */
    private function selectGetLine(): void
    {

        $tabNotFound = array();

        //Extract column not found in target object
        foreach ($this->setData as $column => $alias) {

            $originColumn = explode('.', $column);


            $finalColumn = null;

            //If suffix exists, get orginal column name, else it's already orginal name
            if (count($originColumn) > 1) {
                $finalColumn = $originColumn[1];
            } else {
                $finalColumn = $originColumn[0];
            }


            if (!isset($this->propertyMapping[$finalColumn]) || (isset($this->suffixTable) && $this->suffixTable[$this->className] !== $originColumn[0])) {
                $tabNotFound[$column] = $alias;
            } else {

                $this->updateParameters($this->className, $this->propertyMapping[$finalColumn], $column, $alias);

            }
        }


        if (!empty($tabNotFound)) {

            $searchPortion = explode('FROM', $this->request);

            preg_match_all('/[a-zA-Z0-9-_&\/\\\~@#]+\s*AS\s*[A-Za-z0-9-_\.]+/', $searchPortion[1], $table);



            foreach ($tabNotFound as $column => $alias) {
                //For pregmatch function, taken full name table and alias, if full name column (with alias table)
                // is equals to aliasTable . column name inside configuration, then create setter

                foreach ($table[0] as $value) {
                    $tmp = explode('AS', $value);

                    foreach ($this->dependency as $className => $tabColumn) {


                        foreach ($tabColumn as $key => $value) {

                            if ($column === trim($tmp[1]) . '.' . $value['column'] && $value['table'] === trim($tmp[0])) {


                                //if alias is not null, setter with alias name, else, with column name (with alias table)
                                $this->updateParameters($className, $tabColumn[$key], $column, $this->setData[$column]);
                            }
                        }
                    }
                }
            }
        }
    }


    /**
     * Treat an parameter received by select request, whatever is array or scope
     *
     * @param string $fullNameClass
     * @param array $tabConfig
     * @param string $columnName
     * @param bool $dependency
     */
    private function updateParameters(string $fullNameClass, array $tabConfig, string $columnName, ?string $aliasName): void
    {

        $columnName = preg_replace('/[a-zA-Z0-9-_&\/\\\~@#]+\.{1}/', '', $columnName);

        if((isset($this->propertyMapping[$columnName]['type']) && $this->propertyMapping[$columnName]['type'] === 'array') || (isset($this->dependency[$fullNameClass][$columnName]['type']) && $this->dependency[$fullNameClass][$columnName]['type'] === 'array')){

            $this->dataInsert[$fullNameClass][] = "\r\t\t\t\t\t\tif(isset(\$data['" . $aliasName . "']) && !empty(\$data['" . (($aliasName !== null) ? $aliasName : $columnName) . "'])){
                            
                            \$tmp = unserialize(\$data['" . (($aliasName !== null) ? $aliasName : $columnName) . "']);
                            
                            try{
                                foreach(\$tmp as \$value){
                                    " .
                (
                ($tabConfig['scope'] === 'public')
                    ? '$' . $this->getOnlyClassName($fullNameClass) . '->' . $tabConfig['mutator'] . '($value);'
                    : '$' . $this->getOnlyClassName($fullNameClass) . '->' . $tabConfig['mutator'] . ' = $value;'
                )
                . "
                                }  
                            }catch(\Throwable \$th){
                            " .
                (
                ($tabConfig['scope'] === 'public')
                    ? "\t$" . $this->getOnlyClassName($fullNameClass) . '->' . $tabConfig['mutator'] . '($tmp);'
                    : "\t$" . $this->getOnlyClassName($fullNameClass) . '->' . $tabConfig['mutator'] . ' = $tmp;'
                )
                . "
                            }
                        }
                        ";

        }else {

            $this->dataInsert[$fullNameClass][] =
                '$' . $this->getOnlyClassName($fullNameClass) . '->' .
                (
                ($tabConfig['scope'] === 'public')
                    ? $tabConfig['mutator'] . ' = $data["' . (($aliasName !== null) ? $aliasName : $columnName) . '"];'
                    : $tabConfig['mutator'] . '($data["' . (($aliasName !== null) ? $aliasName : $columnName) . '"]);'
                );
        }
    }


    /**
     * Build final cache file
     *
     * @throws \NoMess\Exception\WorkException
     */
    private function buildCache(): void
    {

        $parameter = "NoMess\Database\IPDOFactory \$instance, NoMess\Container\Container \$container";

        $classNameCache = str_replace('=', '', base64_encode($this->className . "::" . $this->method));


        //Add arbitrary parameters given
        $parameter = $this->adjustParameter($parameter);


        $content = "<?php
                
        class " . $classNameCache . "
        {
            public function execute(" . $parameter . "): array
            {
            
                \$database = \$instance->getConnection('" . $this->idConfig . "');
                
                " . $this->buildFileRequest() . "
                
                
                \$tab = array();
                
                while(\$data = \$req->fetch(\PDO::FETCH_ASSOC)){

            
                    \$" . $this->getOnlyClassName($this->className) . " = null;
                    
                    if(!isset(\$tab[\$data['" . $this->actualkeyArray($this->className) . "']])){
                        \$" . $this->getOnlyClassName($this->className) . " = \$container->make(" . $this->className . "::class);\r
                        " . $this->getDataInsertByClass($this->className) . "
                    }else{
                        \$" . $this->getOnlyClassName($this->className) . " = \$tab[\$data['" . $this->actualKeyArray($this->className) . "']];
                    }
                
                
                ";

        $cursor = null;

        foreach($this->dataInsert as $keyClassName => $line){

            if($keyClassName !== $cursor && $keyClassName !== $this->className){
                $content .= "\r\r\t\t\t\t\tif(isset(\$data['" . $this->actualkeyArray($keyClassName) . "'])){
                        $" . $this->getOnlyClassName($keyClassName) . " = \$container->make(" . $keyClassName . "::class);
                    " . $this->getDataInsertByClass($keyClassName) . "
                    \t\$" . $this->getOnlyClassName($this->className) . "->" .
                    (
                        ($this->cache[$this->className]['dependency'][$keyClassName]['scope'] === 'public')
                            ? $this->cache[$this->className]['dependency'][$keyClassName]['mutator'] . " = " . $this->getOnlyClassName($keyClassName)
                            : $this->cache[$this->className]['dependency'][$keyClassName]['mutator'] . "($" . $this->getOnlyClassName($keyClassName) . ");"

                    ) . "
                \t}
                ";
            }
        }

        $content .= "
                            \$tab[$" . $this->getOnlyClassName($this->className) . "->" . $this->propertyMapping[$this->getKeyArray($this->className)]['accessor'] .
                (
                    ($this->propertyMapping[$this->getKeyArray($this->className)]['scope'] === 'public')
                    ? ""
                    : "()"
                ) . "] = $" . $this->getOnlyClassName($this->className) . ";\r
                        \r\t\t\t\t}
                    return \$tab;
            \r\t\t\t}
            
        \r\t\t}";


        $this->registerCache($content, $classNameCache);

    }


    /**
     * Mapping of suffix of table
     */
    private function mappeSuffixTable(): void
    {
        $searchPortion = explode('FROM', $this->request);

        preg_match_all('/[a-zA-Z0-9-_&\/\\\~@#]+\s*AS\s*[A-Za-z0-9-_\.]+/', $searchPortion[1], $table);

        foreach ($table[0] as $value){
            $tmp = explode('AS', $value);

            $find = false;

            if(isset($this->dependency)){
                foreach($this->dependency as $className => $configuration){
                    foreach ($configuration as $array) {
                        if ($array['table'] === trim($tmp[0])) {
                            $find = true;
                            $this->suffixTable[$className] = trim($tmp[1]);
                        }
                    }
                }
            }

            if($find === false){
                $this->suffixTable[$this->className] = trim($tmp[1]);
            }
        }
    }


    /**
     * Return of actual keyArray
     *
     * @param string|null $className
     * @return string|null
     * @throws \Exception
     */
    private function actualkeyArray(?string $className): ?string
    {



        $column = $this->getKeyArray($className);

        if(isset($this->suffixTable[$className])){

            //if alias is defined
            if(isset($this->setData[$this->suffixTable[$className] . '.' . $column])){
                return $this->setData[$this->suffixTable[$className] . '.' . $column];
            }else{
                return $this->suffixTable[$className] . '.' . $column;
            }
        }else{
            //if alias is defined
            if(isset($this->setData[$column])){
                return $this->setData[$column];
            }else{
                return $column;
            }
        }
    }



    /**
     * Return group of line for specific class for building cache
     *
     * @param string $className
     * @return string
     */
    private function getDataInsertByClass(string $className): string
    {

        $content = '';

        foreach($this->dataInsert as $keyClassName => $array) {
            if ($keyClassName === $className) {
                foreach ($array as $line){
                    $content .= $line . "\n\t\t\t\t\t\t";
                }
            }
        }

        $this->internalCursor = $className;

        return $content;
    }


    protected function purge(): void
    {
        parent::purge();
        $this->suffixTable = array();
        $this->internalCursor = null;
        $this->className = null;
        $this->dataInsert = array();
    }

}