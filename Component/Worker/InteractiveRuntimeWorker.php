<?php


namespace NoMess\Component\Worker;

use NoMess\Component\Component;

class InteractiveRuntimeWorker extends Component
{



    /**
     * Créer une tache a efféctuer par le worker
     *
     * @param string $className Nom complet de la class
     * @param string $method Nom de la method à appeler
     * @param array $args tableau d'argument
     * @param string $taskId identifiant de la tache
     * @param bool $permanent Si défini, la tache sera répété de manière permanent
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