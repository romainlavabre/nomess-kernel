<?php

namespace NoMess\Manager;


use NoMess\Components\Slug\Slug;
use NoMess\Container\Container;
use NoMess\Exception\WorkException;

class EntityManager{


    /**
     * @Inject
     */
    protected Container $container;

    /**
     * Hydrate entity
     *
     * @param array $data
     */
	public function hydrate(array $data) : void
	{
		foreach($data as $key => $value){
			$method = 'set' . ucfirst($key);

			if(method_exists($this, $method)){
				$this->$method($value);
			}
		}
	}

    public function generateSlug(string $str): void
    {
        $floorOne = str_replace([' ', '/'], '-', $str);
        $slug = str_replace('\'', '\\', $floorOne);

        $this->setSlug($slug);
    }

    private function setSlug(string $data): void
    {
        $slug = $this->container->get(Slug::class);


        if(property_exists($this, 'slug')) {

            if($this->slug !== null){
                $slug->deleteSlug($data);
            }

            $slug->addSlud($data);

            $this->slug = $data;
        }else{
            throw new WorkException(get_class($this) . ' encountered an error: slug property not found');
        }
    }

}