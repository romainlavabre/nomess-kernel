<?php

namespace NoMess\HttpRequest;

use NoMess\Exception\WorkException;

class HttpRequest
{


    private const SESSION_DATA = 'nomess_persiste_data';

    private ?array $error = array();

    private ?array $success = array();

    private ?array $parameters = array();

    private ?array $render = array();


    public function __construct()
    {

        if (isset($_SESSION[self::SESSION_DATA])) {
            foreach ($_SESSION[self::SESSION_DATA] as $key => $data) {
                if ($key === 'error') {
                    $this->error = $data;
                } else if ($key === 'success') {
                    $this->success = $data;
                } else {
                    $this->parameters[$key] = $data;
                }
            }

            unset($_SESSION[self::SESSION_DATA]);
        }
    }


    /**
     * Add an error
     *
     * @param string $message
     */
    public function setError(string $message): void
    {
        $this->error[] = $message;
    }


    /**
     * Add an success
     *
     * @param string $message
     */
    public function setSuccess(string $message): void
    {
        $this->success[] = $message;
    }


    /**
     * Delete all success message
     */
    public function resetSuccess(): void
    {
        $this->success = null;
    }


    /**
     * Add an parameter of request
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function setParameter($key, $value): void
    {
        $this->parameters[$key] = $value;
    }

    /**
     *
     * Return an paremeter from GET or POST, if conflict exists, POST is the default choice
     * If doesn't exists parameter, null is retuned
     *
     * @param string $index
     * @param bool $escape True by default, htmlspecialchars is apply
     * @return mixed
     */
    public function getParameter(string $index, bool $escape = true)
    {

        if (isset($_POST[$index]) && !empty($_POST[$index])) {

            if ($escape === true) {
                if (is_array($_POST[$index])) {
                    array_walk_recursive($_POST[$index], function ($key, &$value) {
                        $value = htmlspecialchars($value);
                        $value = trim($value);
                    });
                }

                return $_POST[$index];
            } else {
                return $_POST[$index];
            }

        } else if (isset($_GET[$index]) && !empty($_GET[$index])) {

            if ($escape === true) {

                if (is_array($_GET[$index])) {
                    array_walk_recursive($_GET[$index], function ($key, &$value) {
                        $value = htmlspecialchars($value);
                        $value = trim($value);
                    });
                }

                return $_GET[$index];
            } else {
                return $_GET[$index];
            }

        } else {

            return null;
        }
    }


    /**
     * Return all value of POST variable
     *
     * @return array|null
     */
    public function getParameters(): ?array
    {
        return ['POST' => $_POST, 'GET' => $_GET];
    }


    /**
     * Add an temporary value
     *
     * @param string $serviceStamp
     * @param mixed $value
     */
    public function setRender(string $serviceStamp, $value): void
    {
        $this->render[$serviceStamp] = $value;
    }


    /**
     * Get an temporary value
     *
     * @param string $serviceStamp
     * @return mixed
     */
    public function getRender(string $serviceStamp)
    {
        if (array_key_exists($serviceStamp, $this->render)) {
            return $this->render[$serviceStamp];
        }

        throw new WorkException($serviceStamp . ' n\'a rien retourné');
    }


    /**
     * Return the file sended by POST request
     *
     * @param string $index
     * @return array|null
     */
    public function getPart(string $index): ?array
    {
        if (isset($_FILES[$index])) {
            return $_FILES[$index];
        }

        return null;
    }


    /**
     * Return associate cookie of index variable, null if empty of doesn't exists
     *
     * @param string $index
     * @return mixed
     */
    public function getCookie(string $index)
    {
        if (isset($_COOKIE[$index]) && !empty($_COOKIE[$index])) {
            return $_COOKIE[$index];
        } else {
            return null;
        }
    }


    /**
     * Return all data contained in POST, if espcape worth true, htmlspecialchars will be apply in value (recursively)
     *
     * @param bool $escape
     * @return array|null
     */
    public function getPost(bool $escape = false): ?array
    {
        if ($escape === true) {
            array_walk_recursive($_POST, function ($key, &$value) {
                htmlspecialchars($value);
                $value = trim($value);
            });

        }

        return $_POST;
    }

    /**
     * Return all data contained in GET, if espcape worth true, htmlspecialchars will be apply in value (recursively)
     *
     * @param bool $escape
     * @return array|null
     */
    public function getGet(bool $escape = false): ?array
    {
        if ($escape === true) {
            array_walk_recursive($_GET, function ($key, &$value) {
                htmlspecialchars($value);
                $value = trim($value);
            });
        }

        return $_GET;
    }


    /**
     * Return all content of $_SERVER variable
     *
     * @return array
     */
    public function getServer(): array
    {
        return $_SERVER;
    }


    public function getData(): array
    {
        $array = array();

        if (!empty($this->error)) {
            $array['error'] = $this->error;
        }

        if (!empty($this->success)) {
            $array['success'] = $this->success;
        }

        if (!empty($this->parameters)) {
            $array = array_merge($array, $this->parameters);
        }

        return $array;
    }
}
