<?php

namespace NoMess\HttpResponse;

use NoMess\HttpRequest\HttpRequest;


class HttpResponse
{


    /**
     *
     * @var array
     */
    private $action = array();


    /**
     *
     * @var HttpRequest
     */
    private $request;


    /**
     * @Inject
     *
     * @param HttpRequest $request
     */
    public function __construct(HttpRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Créé un cookie, setCookie assouplie la création en accéptant un tableau a une ou plusieur entrées,
     * Elle resoudra par elle-même les convertions
     *
     * @param string $name
     * @param mixed $value
     * @param int $expires
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     *
     * @return bool
     */
    public function addCookie(string $name, $value = "", int $expires = 0, string $path = "", string $domain = "", bool $secure = FALSE, bool $httponly = FALSE) : bool
    {
        $result = false;

        if(is_array($value)){
            foreach($value as $key => $val){
                $this->action['cookie'][] = ['setcookie' => [$name . '[' . $key . ']', $val, $expires, $path, $domain, $secure, $httponly]];
            }

        }else{

            $this->action['cookie'][] = ['setcookie' => [$name, (string)$value, $expires, $path, $domain, $secure, $httponly]];
        }

        return $result;
    }




    /**
     * Supprime le cookie à l'index spécifié
     *
     * @param string $index
     *
     * @return void
     */
    public function removeCookie(string $index) : void
    {
        $cookie = $this->request->getCookie($index);
        $cookie = null;
        $this->action['cookie'][] = ['setcookie' => [$index, null, -1, '/']];
        
    }


    /**
     * Execute les opérations en attente
     *
     * @return void
     */
    public function manage() : void
    {
        if(isset($this->action['cookie'])){
            foreach($this->action['cookie'] as $value){
                foreach($value as $method => $param){
                    call_user_func_array($method, $param);
                }
            }
        }
    }
}