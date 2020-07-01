<?php
namespace Nomess\Initiator\Route;
use Nomess\Internal\Scanner;

class RouteBuilder
{
    use Scanner;

    private const CONTROLLER_DIRECTORY          = ROOT . 'src/Controllers/';

    private array $routes = array();

    private ?string $header = NULL;

    public function build(): array
    {
        $tree = $this->scanRecursive(self::CONTROLLER_DIRECTORY);

        foreach($tree as $directory){

            $content = scandir($directory);

            foreach($content as $file) {
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
            preg_match('/@Route\("[a-z\/0-9-_]+"\)/', $comment, $routeHeader);

            if(is_string($routeHeader)){
                $this->header = $routeHeader;
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
                    preg_match('/"[a-z\/0-9-_{}]+"/', $annotation[0], $route);
                    preg_match('/name=".+"/', $annotation[0], $name);
                    preg_match('/methods="([GETPOS,]+)"/', $annotation[0], $requestMethod);

                    if(!empty($route)) {
                        $this->routes[$this->header . str_replace('"', '', $route[0])] = [
                            'name' => (!empty($name)) ? str_replace(['name="', '"'], '', $name[0]) : NULL,
                            'request_method' => (!empty($reflectionMethod) && isset($requestMethod[1])) ? $requestMethod[1] : NULL,
                            'method' => $reflectionMethod->getName(),
                            'controller' => $reflectionMethod->getDeclaringClass()->getName()
                        ];
                    }
                }
            }
        }
    }

    private function getNamespace(string $filename): string
    {
        $filename = str_replace([ROOT, 'src/Controllers/', '.php'], '', $filename);
        $filename = str_replace('/', '\\', $filename);

        return "App\\Controllers\\$filename";
    }
}
