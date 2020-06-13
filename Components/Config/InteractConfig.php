<?php


namespace NoMess\Components\Config;


use NoMess\Components\Component;
use NoMess\Exception\WorkException;

class InteractConfig extends Component
{

    private const BASE              = ROOT . 'App/config/';

    private string $filename;
    private ?array $config;


    /**
     * Return an array contained all configuration for specified file
     *
     * @param string $filename
     * @return array
     * @throws WorkException
     */
    public function get(string $filename): array
    {

        if(!file_exists(self::BASE . $filename . '.php')){
            throw new WorkException('InteractConfig encountered an error: file ' . self::BASE . $filename . ' not found');
        }

        $fileConfig = require(self::BASE . $filename . '.php');

        $this->config = $fileConfig;
        $this->filename = self::BASE . $filename . '.php';

        return $fileConfig;
    }


    /**
     * Save the modifications
     *
     * @param array $data
     */
    public function save(array $data): void
    {
        $dataConverted = $this->dataConverter(array_replace($this->config, $data));
        $this->register($dataConverted);
    }


    private function register(string $data): void
    {
        echo file_put_contents($this->filename, "<?php \r\rreturn [\n\t" . $data . "\r];");
        chmod($this->filename, 0777);
    }


    /**
     * Convert array to string
     *
     * @param array $array
     * @param int $floor
     * @return string
     */
    private function dataConverter(array $array, int $floor = 1): string
    {

        $indent = "";

        for($i = 0; $i < $floor; $i++){
            $indent .= "\t";
        }

        $data = "";

        foreach ($array as $key => $value){
            if(!is_array($value)){

                if(class_exists($key)){
                    $data .= "\n$indent \\$key::class => '$value',";
                }else {
                    $data .= "\n$indent'$key' => '$value',";
                }
            }else{
                $data .= "\n$indent'$key' => [" . $this->dataConverter($value, $floor + 1) . "\n$indent],";
            }
        }


        return $data;
    }

}