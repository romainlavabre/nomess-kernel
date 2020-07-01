<?php

namespace Nomess\Components\EntityManager;

use Nomess\Annotations\Inject;
use Nomess\Components\EntityManager\Resolver\DeleteResolver;
use Nomess\Components\EntityManager\Resolver\SelectResolver;
use Nomess\Components\EntityManager\Resolver\UpdateResolver;
use Nomess\Container\Container;
use Nomess\Exception\ORMException;
use Nomess\Helpers\DataHelper;
use Nomess\Http\HttpRequest;
use RedBeanPHP\R;

class EntityManager implements EntityManagerInterface
{

    use DataHelper;

    private const STORAGE_CACHE         = ROOT . 'var/cache/em/';

    private const CREATE                = 'create';
    private const UPDATE                = 'update';
    private const DELETE                = 'delete';

    private array $entity = array();
    private bool $hasConfigured = FALSE;

    /**
     * @Inject()
     */
    private Container $container;

    /**
     * @Inject()
     */
    private HttpRequest $request;

    /**
     * @Inject()
     */
    private UpdateResolver $updateResolver;

    /**
     * @Inject()
     */
    private DeleteResolver $deleteResolver;

    /**
     * @Inject()
     */
    private Config $config;


    public function find(string $classname, ?string $idOrSql = NULL, ?array $parameters = NULL )
    {
        $this->initConfig();
        return $this->container->get(SelectResolver::class)->resolve($classname, $idOrSql, $parameters);
    }

    public function create(object $object): self
    {
        $this->entity[] = [
            'context' => self::CREATE,
            'classname' => $this->getShortenName($object),
            'fullClassname' => get_class($object),
            'data' => $object,
            'configuration' => array()
        ];

        return $this;
    }

    public function update(object $object): self
    {
        $this->entity[] = [
            'context' => self::UPDATE,
            'classname' => $this->getShortenName($object),
            'fullClassname' => get_class($object),
            'data' => $object,
            'configuration' => array()
        ];

        return $this;
    }

    public function delete(?object $object): self
    {
        if($object !== NULL) {
            $this->entity[] = [
                'context' => self::DELETE,
                'classname' => $this->getShortenName($object),
                'fullClassname' => get_class($object),
                'data' => $object,
                'configuration' => array()
            ];
        }

        return $this;
    }

    public function setCascade(string $classname, bool $apply): self
    {
        $position = count($this->entity) - 1;

        $this->entity[$position]['configuration'][$classname] = $apply;

        return $this;
    }

    public function register(): bool
    {
        $this->initConfig();

        if(!empty($this->entity)){
            R::begin();

            try {
                foreach($this->entity as $data) {

                    if($data['context'] === self::CREATE || $data['context'] === self::UPDATE) {
                        $beans = $this->updateResolver->resolve($data);

                        if(!empty($beans)) {

                            R::storeAll($beans);
                        }
                    }else{
                        $beans = $this->deleteResolver->resolve($data);
                        R::trashAll($beans);
                    }
                }
            }catch(\Throwable $e){
                R::rollback();

                if(NOMESS_CONTEXT === 'DEV'){
                    throw new ORMException($e->getMessage() . ' in ' . $e->getFile() . ' line ' . $e->getLine());
                }else{
                    $this->request->resetSuccess();
                    $this->request->setError($this->get('orm_error'));
                }

                return FALSE;
            }

            R::commit();
            R::close();
        }

        return TRUE;
    }

    private function initConfig(): void
    {
        if(!$this->hasConfigured) {
            $this->hasConfigured = TRUE;
            $this->config->init();
        }
    }

    private function getShortenName(object $object): string
    {
        return substr(strrchr(get_class($object), '\\'), 1);
    }

}
