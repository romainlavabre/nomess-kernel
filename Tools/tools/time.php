<?php

class Time{

	private $xdebug;

	public function getXdebug() : ?float
	{
		return $this->xdebug;
	}

	public function setXdebug(float $setter) : void
	{
		$this->xdebug = number_format($setter, 3, '.', '');
	}
}