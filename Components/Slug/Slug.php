<?php

namespace NoMess\Components\Slug;

use NoMess\ObserverInterface;

class Slug implements ObserverInterface
{

    private const PATH_CACHE            = ROOT . 'App/var/cache/slug/slug.php';


    private ?array $contentRule = array();
    private bool $update = false;


    public function __construct()
    {

        if(file_exists(self::PATH_CACHE)) {
            $tmp = require self::PATH_CACHE;
            $this->contentRule = unserialize($tmp);
        }
    }

    public function searchSlug(string $param): ?string
    {
        if(array_key_exists($param, $this->contentRule)){
            return $this->contentRule[$param];
        }

        return null;
    }


    public function addSlug(string $str): void
    {
        $this->contentRule[] = $str;
        $this->update = true;
    }

    public function deleteSlug(string $str): void
    {
        unset($this->contentRule[$str]);
        $this->update = true;
    }


    private function persists() : void
    {
        if($this->update === true) {
            file_put_contents(self::PATH_CACHE, '<?php return \'' . serialize($this->contentRule) . '\';');
        }
    }

    public function notifiedInput(): void {}


    public function notifiedOutput(): void
    {
        $this->persists();
    }
}
