<?php

namespace NoMess\Manager;


class EntityManager implements \JsonSerializable{

	/**
	 * Hydrate l'entité
	 *
	 * @param array $donnees
	 * @return void
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

	

	/**
	 *
	 * @return array
	 */
	public function jsonSerialize() : array
    {
        return get_object_vars($this);
    }

	public function __destruct() {}
}