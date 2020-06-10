<?php


namespace NoMess\Service\Helpers;


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

trait Response
{
    public function response(int $code): void
    {
        http_response_code($code);

        $tabError = require ROOT . 'App/config/error.php';

        if(strpos($tabError[$code], '.twig')){
            if(file_exists(ROOT . 'Web/public/' . $tabError[$code])) {
                $this->bindTwig($tabError[$code]);
            }
        }else{
            if(file_exists(ROOT . $tabError[$code])) {
                include(ROOT . $tabError[$code]);
            }
        }
        die;
    }

    private function bindTwig(string $template) : void
    {
        $loader = new FilesystemLoader('Web/public/');
        $engine = new Environment($loader, [
            'cache' => false,
        ]);

        $engine->addExtension(new \Twig\Extension\DebugExtension());

        echo $engine->render($template, [
            'URL' => URL,
            'WEBROOT' => WEBROOT
        ]);
    }
}