<?php
namespace Nomess\Initiator\Route;
use Nomess\Component\Config\ConfigStoreInterface;
use Nomess\Exception\ConflictException;
use Nomess\Internal\Scanner;

class RouteBuilder
{
    use Scanner;
    private ConfigStoreInterface $configStore;
    private array $routes;
    private ?string $header = NULL;
    
    public function __construct(ConfigStoreInterface $configStore)
    {
        $this->configStore = $configStore;
    }
    
    
    public function build(): array
    {
        $this->routes = array();
        $tree = $this->scanRecursive(
            $this->configStore->get(ConfigStoreInterface::DEFAULT_NOMESS)['general']['path']['default_controller']
        );

        foreach($tree as $directory){

            $content = scandir($directory);

            foreach($content as $file) {
                $this->header = NULL;
                if($file !== '.' && $file !== '..' && strpos($file, '.php') !== FALSE) {

                    $reflectionClass = new \ReflectionClass($this->getNamespace($directory . $file));

                    $this->getHeader($reflectionClass);
                    $this->getAnnotations($reflectionClass->getMethods());
                }
            }
        }

        return $this->routes;
    }

    private function getHeader(\ReflectionClass $reflectionClass): void
    {
        $comment = $reflectionClass->getDocComment();

        if(strpos($comment, '@Route') !== FALSE){
            preg_match('/@Route\("([a-z\/0-9-_]+)"\)/', $comment, $routeHeader);

            if(isset($routeHeader[1])){
                $this->header = $routeHeader[1];
            }
        }
    }

    /**
     * @param \ReflectionMethod[]|null $reflectionMethods
     */
    private function getAnnotations(?array $reflectionMethods): void
    {
        if(!empty($reflectionMethods)){
            foreach($reflectionMethods as $reflectionMethod){
                $comment = $reflectionMethod->getDocComment();

                preg_match('/@Route\(.+\)/', $comment, $annotation);

                if(!empty($annotation)){
                    preg_match('/"([a-z\/0-9-_{}]+)"/', $annotation[0], $route);
                    preg_match('/name="([A-Za-z._-]+)"/', $annotation[0], $name);
                    preg_match('/methods="([GETPOSUDL,]+)"/', $annotation[0], $requestMethod);
                    preg_match('/requirements=\[(".+" *=> *".+",? ?)\]/', $annotation[0], $requirements);
                    
                    if(isset($route[1])) {
                        $route = $this->header . $route[1];
                        $this->isUniqueRoute($route, $reflectionMethod->getDeclaringClass());
                        
                        $this->routes[$route] = [
                            'name' => (isset($name[1])) ? $name[1] : NULL,
                            'request_method' => (isset($requestMethod[1])) ? $requestMethod[1] : NULL,
                            'method' => $reflectionMethod->getName(),
                            'controller' => $reflectionMethod->getDeclaringClass()->getName(),
                            'requirements' => $this->requirementsToArray($requirements)
                        ];
                    }
                }
            }
        }
    }

    private function requirementsToArray(array $requirements): array
    {
        $list = array();

        if(isset($requirements[1])){
            $str = preg_replace(['/ ?=> ?/', '/,/', '/ */'], '', $requirements[1]);
            $str = str_replace('""', '|', $str);
            $str = str_replace('"', '', $str);

            $key = NULL;

            foreach(explode('|', $str) as $data){
                if($key === NULL){
                    $list[$data] = NULL;
                    $key = $data;
                }else{
                    $list[$key] = $data;
                    $key = NULL;
                }
            }
        }
        return $list;
    }

    private function getNamespace(string $filename): string
    {
        $filename = str_replace([
            $this->configStore->get(ConfigStoreInterface::DEFAULT_NOMESS)['general']['path']['default_controller'],
            '.php'
                                ], '', $filename);
        $filename = str_replace('/', '\\', $filename);

        return "App\\Controller\\$filename";
    }
    
    private function isUniqueRoute(string $route, \ReflectionClass $reflectionClass): void
    {
        if(array_key_exists($route, $this->routes)){
            throw new ConflictException('Your route "' . $route . '" declared in "' . $reflectionClass->getName() . '::class" is already used by ' .
            $this->routes[$route]['controller'] . ' for method ' . $this->routes[$route]['method']);
        }
    }
}
