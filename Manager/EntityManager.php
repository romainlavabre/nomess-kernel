<?php

namespace NoMess\Manager;


class EntityManager{

	/**
	 * Hydrate entity
	 *
	 * @param array $donnees
	 */
	public function hydrate(array $donnees) : void
	{
		foreach($donnees as $key => $value){
			$method = 'set' . ucfirst($key);

			if(method_exists($this, $method)){
				$this->$method($value);
			}
		}
	}


	public function __destruct() {}
}