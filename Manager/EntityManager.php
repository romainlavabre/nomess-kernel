<?php

namespace Nomess\Manager;


class EntityManager{


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

}
