<?php


namespace NoMess\Component\Worker;

use NoMess\Component\Component;

class InteractiveRuntimeWorker extends Component
{



    /**
     * Create task to be executed by worker
     *
     * @param string $className Full name class
     * @param string $method Name of method to called
     * @param array $args Array of arguments
     * @param string $taskId Task identifiant
     * @param bool $permanent id defined, the task will be permanently repeat
     *
     * @return void
     */
    public function createTask(string $className, string $method, array $args, string $taskId, bool $permanent = false) : void
    {

        $valPerm = ($permanent === '' || $permanent === 0 || $permanent === false) ? 'false' : 'true';

        $content = "<?php
        
        \$param = '" . serialize($args) . "';

        return [
            'function' => call_user_func_array([\$container->get(" . $className . "::class), '" . $method . "'], unserialize(\$param)),
            'taskId' => '" . $taskId . "',
            'permanent' => " . $valPerm . "
        ];
        ";

        file_put_contents(__DIR__ . '/Storage/' . uniqid('runtime_') . '.php', $content);

    }
}