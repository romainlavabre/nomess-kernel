<?php


namespace NoMess\Component\LightPersists;

use Throwable;
use NoMess\Component\Component;
use NoMess\Exception\WorkException;
use NoMess\HttpRequest\HttpRequest;
use NoMess\HttpResponse\HttpResponse;
use NoMess\ObserverInterface;
use Psr\Container\ContainerInterface;


/**
 * LightPersists est un conteneur de persistance légé et plus malléable que les sessions, 
 * le cookie généré expire au bout de 10 ans, ainsi, c'est au développeur de supprimer manuellement les données 
 */
class LightPersists extends Component implements ObserverInterface
{

    private const COOKIE_NAME           = 'psd_';
    private const STORAGE_PATH          = '/var/nomess/';



    /**
     * @Inject
     *
     * @var ContainerInterface
     */
    private $container;


    /**
     * Contenu
     *
     * @var array
     */
    private $content;


    /**
     * Identifiant du fichier
     *
     * @var string
     */
    private $id;


    /**
     * Retourne la valeur associé à l'index par référence
     * Null si elle n'éxiste pas
     *
     * @param mixed $index
     *
     * @return void
     */
    public function &getReference($index)
    {

        if(isset($this->content[$index])){
            return $this->content[$index];
        }else{
            return null;
        }
    }



    /**
     * Ajoute une valeur
     *
     * @param mixed $key
     * @param mixed $value
     * @param bool $reset Supprime la clé $key avant toute insertion
     *
     * @return void
     */
	public function set($key, $value, $reset = false) : void
	{
		if($reset === true){
			unset($this->content[$key]);
		}

        if(\is_array($value)){

            foreach($value as $keyArray => $valArray){

                $this->content[$key][$keyArray] = $valArray;
            }

        }else{
            $this->content[$key] = $value;
        }
    }
    

    /**
     * Retourne la valeur associé a l'index
     * Null si elle n'éxiste pas
     *
     * @param mixed $index
     *
     * @return mixed
     */
    public function get($index) 
	{

        if(isset($this->content[$index])){
            return $this->content[$index];
        }else if($index === null){
            return $this->content;
        }else{
            return null;
        }
    }
    

    /**
     * Supprime une paire clé/valeur
     *
     * @param string $index
     *
     * @return void
     */
    public function delete(string $index){

        if($this->id === null){
            $this->getContent();
        }

        if(array_key_exists($index, $this->content)){
            unset($this->content[$index]);
        }
    }


    /**
     * Supprime le fichier de persistance
     * 
     * @throws WorkException
     *
     * @return void
     */
    public function purge() : void
    {

        /**
         * @var HttpResponse
         */
        $response = $this->container->get(HttpResponse::class);

        $response->removeCookie(self::COOKIE_NAME);

        try{
            unlink(self::STORAGE_PATH . $this->id);
        }catch(Throwable $e){
            throw new WorkException('Impossible d\'acceder à' . self::STORAGE_PATH . ' message: ' . $e->getMessage());
        }
    }




    /**
     * Perists les modifications
     * 
     * @throws WorkException
     *
     * @return void
     */
    private function persists() : void
    {

        try{
            file_put_contents(self::STORAGE_PATH . $this->id . '.txt', serialize($this->content));
        }catch(Throwable $e){
            throw new WorkException('Impossible d\'acceder à' . self::STORAGE_PATH . ' message: ' . $e->getMessage());
        }
    }



    /**
     * Recupère le contenu du fichier ou le créer
     * 
     * @throws WorkException
     *
     * @return void
     */
    private function getContent() : void
    {

        /**
         * @var HttpRequest
         */
        $request = $this->container->get(HttpRequest::class);

        $id = $request->getCookie(self::COOKIE_NAME);


        if($id === null){

            /**
             * @var HttpResponse
             */
            $response = $this->container->get(HttpResponse::class);

            $id = uniqid();

            $response->addCookie(self::COOKIE_NAME, $id, time() + 60 * 60 *24 * 3650, '/' );

            try{
                file_put_contents(self::STORAGE_PATH . $id . '.txt', '');
            }catch(Throwable $e){
                throw new WorkException('Impossible d\'acceder à' . self::STORAGE_PATH . ' message: ' . $e->getMessage());
            }

        }else{
            try{
                $data = file_get_contents(self::STORAGE_PATH . $id . '.txt');
                //r($this->content);
                $this->content = unserialize($data);
            }catch(Throwable $e){
                throw new WorkException('Impossible d\'acceder à' . self::STORAGE_PATH . ' message: ' . $e->getMessage());
            }
        }

        $this->id = $id;
    }


    public function notifiedInput(): void
    {
        $this->getContent();
    }

    public function notifiedOutput(): void
    {
        $this->persists();
    }
}