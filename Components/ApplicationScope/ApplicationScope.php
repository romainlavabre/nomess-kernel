<?php


namespace Nomess\Components\ApplicationScope;


class ApplicationScope
{
    private const PATH_CACHE            = ROOT . 'var/cache/as/as.php';

    private ?array $data;
    private bool $update = false;

    public function __construct()
    {
        $this->loadData();
    }

    /**
     * Get data
     *
     * @param string $index
     * @return mixed|null
     */
    public function get(string $index)
    {
        return (isset($this->data[$index])) ? $this->data[$index] : NULL;
    }


    /**
     * Update data
     *
     * @param $key
     * @param $value
     * @param bool $reset
     */
    public function set($key, $value, $reset = false): void
    {
        if ($reset === true) {
            unset($this->data[$key]);
        }

        if (\is_array($value)) {

            foreach ($value as $keyArray => $valArray) {

                $this->data[$key][$keyArray] = $valArray;
            }

        } else {
            $this->data[$key] = $value;
        }

        $this->update = true;
    }

    private function loadData(): void
    {
        if(file_exists(self::PATH_CACHE)){
            $tmp = require self::PATH_CACHE;
            $this->data = unserialize($tmp);
        }else{
            $this->data = array();
        }
    }

    private function persistsData(): void
    {
        if($this->update === true){
            file_put_contents(self::PATH_CACHE, '<?php return \'' . str_replace('\'', '\\\'', serialize($this->data)) . '\';');
        }
    }

    public function __destruct()
    {
        $this->persistsData();
    }
}
