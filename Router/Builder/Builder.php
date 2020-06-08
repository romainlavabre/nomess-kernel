<?php


namespace NoMess\Router\Builder;


use NoMess\Exception\WorkException;



class Builder
{

    private const DIR               = ROOT . 'App/src/Controllers/';
    private const PATH_CACHE        = ROOT . 'App/var/cache/routes/';


    /**
     * Contains all routes
     */
    private array $routes = array();

    public function buildRoute(): void
    {

        $folders = $this->scanRecursive();

        foreach ($folders as $path) {
            $content = scandir($path);

            foreach ($content as $item){
                if(pathinfo($path . $item, PATHINFO_EXTENSION) === 'php'){
                    $reflectionClass = new \ReflectionClass($this->getClassName($path . $item));

                    $auth = $this->getAuth($reflectionClass);

                    foreach ($this->getRoute($reflectionClass) as $route){
                        if(!empty($route)) {
                            $this->addRoute($route, $reflectionClass->getName(), $path . $item, $auth);
                        }
                    }
                }
            }
        }


        $this->register();
    }


    /**
     * Return full class name
     *
     * @param string $filename
     * @return string
     */
    private function getClassName(string $filename): string
    {
        $path = str_replace([ROOT . 'App/src/Controllers/', '.php'], '', $filename);

        $className = str_replace('/', '\\', $path);

        return 'App\\Controllers\\' . $className;

    }

    /**
     * Return all routes
     *
     * @param \ReflectionClass $reflectionClass
     * @return array
     */
    private function getRoute(\ReflectionClass $reflectionClass): array
    {

        $array = array();

        $comment = $reflectionClass->getDocComment();

        if(strpos($comment, '@Route') !== false ){
            $lineComment = explode('*', $comment);

            foreach ($lineComment as $line){
                if(strpos($line, '@Route') !== false){

                    $array[] = trim(str_replace(['@Route(', ')'], '', $line));
                }
            }
        }


        return $array;
    }


    /**
     * Return auth
     *
     * @param \ReflectionClass $reflectionClass
     * @return string|null
     */
    private function getAuth(\ReflectionClass $reflectionClass): ?string
    {
        $comment = $reflectionClass->getDocComment();

        if(strpos($comment, '@Filter')){
            $lineComment = explode('*', $comment);

            foreach ($lineComment as $line){
                if(strpos($comment, '@Filter')){
                    return trim(str_replace(['@Filter(', ')'], '', $line));
                }
            }
        }

        return null;
    }



    /**
     * @param string $url
     * @param string $controllers
     * @param string $path
     * @param string $filter
     * @throws WorkException
     */
    private function addRoute(string $url, string $controllers, string $path, ?string $filter): void
    {

        if(!array_key_exists($url, $this->routes)){

            $this->routes[$url] = [
                'controller' => $controllers,
                'path' => $path,
                'filter' => $filter
            ];
        }else{
            throw new WorkException('RoutesBuilder encountered an error: Duplicatation of url ' . $url);
        }
    }


    /**
     * Register file cache
     */
    private function register(): void
    {

        $data = '<?php return \'' . serialize($this->routes) . '\';';

        file_put_contents(self::PATH_CACHE . 'route.php', $data);
    }


    /**
     * Return tree of directory 'App/src/Controllers'
     *
     * @return array
     */
    public function scanRecursive() : array
    {
        $pathDirSrc = self::DIR;

        $tabGeneral = scandir($pathDirSrc);

        $tabDirWait = array();

        $dir = $pathDirSrc;

        $noPass = count(explode('/', $dir));

        do{
            $stop = false;

            do{
                $tabGeneral = scandir($dir);
                $dirFind = false;

                for($i = 0; $i < count($tabGeneral); $i++){
                    if(is_dir($dir . $tabGeneral[$i] . '/') && $tabGeneral[$i] !== '.' && $tabGeneral[$i] !== '..'){
                        if(!$this->controlDir($dir . $tabGeneral[$i] . '/', $tabDirWait)){
                            $dir = $dir . $tabGeneral[$i] . '/';
                            $dirFind = true;
                            break;
                        }
                    }
                }

                if(!$dirFind){
                    $tabDirWait[] = $dir;
                    $tabEx = explode('/', $dir);
                    unset($tabEx[count($tabEx) - 2]);
                    $dir = implode('/', $tabEx);
                }

                if(count(explode('/', $dir)) < $noPass){
                    $stop = true;
                    break;
                }
            }
            while($dirFind === true);
        }
        while($stop === false);

        return $tabDirWait;
    }


    private function controlDir(string $path, array $tab) : bool
    {
        foreach($tab as $value){
            if($value === $path){
                return true;
            }
        }

        return false;
    }

}