<?php


namespace NoMess\Components\PersistsManager\ResolverRequest;



class ResolverCreate extends ResolverImpactData
{

    /**
     * Launch build cache file
     *
     * @throws \NoMess\Exception\WorkException
     */
    public function execute()
    {
        $this->buildParameter();
        $this->buildCache();
        $this->registerInitialConfig();
    }


    /**
     * Build cache file
     */
    private function buildCache(): void
    {



        $className = $this->generateClassName($this->className . "::" . $this->method);
        $parameter = "NoMess\Database\IPDOFactory \$instance, NoMess\Container\Container \$container";

        $parameter = $this->adjustParameter($parameter);

        $content = "<?php
        
        
        class " . $className . "
        {
           public function execute(" . $parameter . ")
            {
            
                \$database = \$instance->getConnection('" . $this->idConfig . "');
                
                " . $this->buildFileRequest() . "
          
                try{
                    return \$database->lastInsertId();
                }catch(\Throwable \$th){}
            }
        }      
        ";


        $this->registerCache($content, $className);
    }
}