<?php


namespace NoMess\Components\ApplicationScope;


use NoMess\Components\Component;
use NoMess\ObserverInterface;

class ApplicationScope extends Component implements ObserverInterface
{
    private const PATH_CACHE            = ROOT . 'App/var/cache/as/as.php';

    private ?array $data;
    private bool $update = false;


    /**
     * Get data
     *
     * @param string $index
     * @return mixed|null
     */
    public function get(string $index)
    {
        if(isset($this->data[$index])){
            return $this->data[$index];
        }

        return null;
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
            $this->data = $tmp;
        }else{
            $this->data = array();
        }
    }

    private function persistsData(): void
    {
        if($this->update === true){
            file_put_contents(self::PATH_CACHE, '<?php return \'' . serialize($this->data) . '\';');
        }
    }

    public function notifiedInput(): void
    {
        $this->loadData();
    }

    public function notifiedOutput(): void
    {
        $this->persistsData();
    }
}