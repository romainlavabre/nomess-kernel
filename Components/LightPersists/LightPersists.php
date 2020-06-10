<?php


namespace NoMess\Components\LightPersists;

use NoMess\Components\Component;
use NoMess\Container\Container;
use NoMess\Exception\WorkException;
use NoMess\HttpRequest\HttpRequest;
use NoMess\HttpResponse\HttpResponse;
use NoMess\ObserverInterface;
use Throwable;



class LightPersists extends Component implements ObserverInterface
{

    private const COOKIE_NAME = 'psd_';
    private const STORAGE_PATH = '/var/nomess/';


    private Container $container;

    private ?array $content = null;


    /**
     * Identifier of file
     */
    private ?string $id = null;


    /**
     * @Inject
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    /**
     * Return value associate to index variable or null if doesn't exists
     *
     * @param mixed $index
     */
    public function &getReference($index)
    {

        if (isset($this->content[$index])) {
            return $this->content[$index];
        }
    }


    /**
     * Add value in container
     *
     * @param mixed $key
     * @param mixed $value
     * @param bool $reset Delete value associate to index before instertion
     *
     * @return void
     */
    public function set($key, $value, $reset = false): void
    {
        if ($reset === true) {
            unset($this->content[$key]);
        }

        if (\is_array($value)) {

            foreach ($value as $keyArray => $valArray) {

                $this->content[$key][$keyArray] = $valArray;
            }

        } else {
            $this->content[$key] = $value;
        }
    }


    /**
     * Return value associate to index variable or null if doesn't exists
     *
     * @param mixed $index
     *
     * @return mixed
     */
    public function get($index)
    {

        if (isset($this->content[$index])) {
            return $this->content[$index];
        } else if ($index === null) {
            return $this->content;
        } else {
            return null;
        }
    }


    /**
     * Delete an pair key/value
     *
     * @param string $index
     *
     * @return void
     */
    public function delete(string $index)
    {

        if ($this->id === null) {
            $this->getContent();
        }

        if (array_key_exists($index, $this->content)) {
            unset($this->content[$index]);
        }
    }


    /**
     * Delete the persistence file
     *
     * @throws WorkException
     */
    public function purge(): void
    {

        /**
         * @var HttpResponse
         */
        $response = $this->container->get(HttpResponse::class);

        $response->removeCookie(self::COOKIE_NAME);

        try {
            unlink(self::STORAGE_PATH . $this->id);
        } catch (Throwable $e) {
            throw new WorkException('Impossible d\'acceder à' . self::STORAGE_PATH . ' message: ' . $e->getMessage());
        }
    }


    /**
     * Save changes
     *
     * @return void
     * @throws WorkException
     *
     */
    private function persists(): void
    {

        try {
            file_put_contents(self::STORAGE_PATH . $this->id . '.txt', serialize($this->content));
        } catch (Throwable $e) {
            throw new WorkException('Impossible d\'acceder à' . self::STORAGE_PATH . ' message: ' . $e->getMessage());
        }
    }


    /**
     * Get content of file or create it
     *
     * @return void
     * @throws WorkException
     *
     */
    private function getContent(): void
    {

        /**
         * @var HttpRequest
         */
        $request = $this->container->get(HttpRequest::class);

        $id = $request->getCookie(self::COOKIE_NAME);


        if ($id === null) {

            $response = $this->container->get(HttpResponse::class);

            $id = uniqid();

            $response->addCookie(self::COOKIE_NAME, $id, time() + 60 * 60 * 24 * 3650, '/');

            try {
                file_put_contents(self::STORAGE_PATH . $id . '.txt', '');
            } catch (Throwable $e) {
                throw new WorkException('Impossible d\'acceder à' . self::STORAGE_PATH . ' message: ' . $e->getMessage());
            }

        } else {
            try {
                $data = file_get_contents(self::STORAGE_PATH . $id . '.txt');
                $this->content = unserialize($data);
            } catch (Throwable $e) {
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
