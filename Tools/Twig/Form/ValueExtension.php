<?php


namespace Nomess\Tools\Twig\Form;


use Nomess\Exception\NotFoundException;
use Nomess\Http\HttpRequest;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ValueExtension extends AbstractExtension
{
    private $data = NULL;
    private ?\ReflectionClass $reflectionClass = NULL;
    private ?object $instance = NULL;

    public function __construct(?array $post)
    {
        $this->data = $post;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('form_bind', [$this, 'bind']),
            new TwigFunction('form_value', [$this, 'value']),
            new TwigFunction('form_select', [$this, 'select']),
        ];
    }

    public function bind(?object $instance)
    {
        if($instance !== NULL) {
            $this->reflectionClass = new \ReflectionClass($instance);
            $this->instance = $instance;
        }
    }

    public function value(string $name, ?string $propertyName = NULL, string $default = NULL): void
    {
        if(isset($this->data[$name])){
            echo $this->data[$name];
            return;
        }
        
        if($this->reflectionClass !== NULL){

            $reflectionProperty = NULL;

            try{
                if($propertyName != NULL){
                    $reflectionProperty = $this->reflectionClass->getProperty($propertyName);
                }else {
                    $reflectionProperty = $this->reflectionClass->getProperty($name);
                }
            }catch(\Throwable $e){}

            if($reflectionProperty !== NULL){
                if(!$reflectionProperty->isPublic()){
                    $reflectionProperty->setAccessible(TRUE);
                }

                echo $reflectionProperty->getValue($this->instance);
                return;
            }
        }
        
        if(!empty($default)){
            echo $default;
        }
    }

    public function select(string $name, string $value, ?array $searchData = NULL, ?string $propertyName = NULL): void
    {
        if(isset($this->data[$name])){
            if(is_array($this->data[$name])){
                foreach($this->data[$name] as $data){
                    if($data === $value){
                        echo 'selected';
                        break;
                    }
                }
            }elseif((string)$this->data[$name] === (string)$value) {
                echo 'selected';
            }
        }elseif($searchData === NULL){
            if($this->reflectionClass !== NULL){

                $reflectionProperty = NULL;

                try{
                    if($propertyName != NULL){
                        $reflectionProperty = $this->reflectionClass->getProperty($propertyName);
                    }else {
                        $reflectionProperty = $this->reflectionClass->getProperty($name);
                    }
                }catch(\Throwable $e){}
    
                if($reflectionProperty !== NULL){
                    if(!$reflectionProperty->isPublic()){
                        $reflectionProperty->setAccessible(TRUE);
                    }

                    $valueProperty = $reflectionProperty->getValue($this->instance);

                    if(is_array($valueProperty)){
                        foreach($valueProperty as $data){
                            if(is_object($data) && (string)$data->getId() === (string)$value){
                                echo 'selected';
                                break;
                            }

                            if((string)$data === (string)$value){
                                echo 'selected';
                                break;
                            }
                        }
                    }else{
                        try {
                            if((is_object($valueProperty) && (string)$valueProperty->getId() === (string)$value)
                                || (string)$valueProperty === (string)$value) {
    
                                echo 'selected';
                            }
                        }catch(\Throwable $e){}
                    }
                }
            }
        }else{
            foreach($searchData as $data){
                if($value === $data){
                    echo 'selected';
                    break;
                }
            }
        }
    }

}
