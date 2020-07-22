<?php

namespace Nomess\Tools\Twig;

use InvalidArgumentException;
use Nomess\Exception\NotFoundException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PathExtension extends AbstractExtension
{

    private const CACHE         = ROOT . 'var/cache/routes/route.php';

    public function getFunctions()
    {
        return [
            new TwigFunction('path', [$this, 'path']),
        ];
    }

    public function path(string $routeName, array $param = NULL)
    {
        $routes = $this->getCache();

        foreach($routes as $key => $route){
            if($route['name'] === $routeName){
                
                if(strpos($key, '{') !== FALSE){
                    $sections = explode('/', $key);

                    foreach($sections as &$section){

                        if(strpos($section, '{') !== FALSE){
                            $purgedSection = str_replace(['{', '}'], '', $section);

                            if(!empty($param) && array_key_exists($purgedSection, $param)){
                                $section = $param[$purgedSection];
                                
                                if(empty($section)){
                                    throw new \Exception('Your parameter "' . $purgedSection . '" for route ' . $routeName . ' is void');
                                }
                                unset($param[$purgedSection]);
                            }else{
                                throw new InvalidArgumentException('Missing an dynamic data in your url');
                            }
                        }
                    }

                    $key = implode('/', $sections);
                }

                if(strpos($key, '{')){
                    throw new InvalidArgumentException('Missing an dynamic data in your url');
                }

                if(!empty($param)){
                    $i = 0;

                    foreach($param as $index => $value) {

                        if($i === 0){
                            $key .= "?$index=$value";
                            $i++;
                        }else{
                            $key .= "&$index=$value";
                        }
                    }
                }

                return $key;
            }
        }

        throw new NotFoundException("Your route $routeName has not found");
    }

    private function getCache(): array
    {
        if(file_exists(self::CACHE)){
            return require self::CACHE;
        }else{
            throw new NotFoundException('Impossible to find the cache file of route');
        }
    }
}
