<?php

namespace NoMess\Database;


class Database
{

    private const KEY           = 'nomess_db';
    private const CREATE        = 'create:';
    private const UPDATE        = 'update:';
    private const DELETE        = 'delete:';

    /**
     * Ajoute à la pile la créetion d'un objet et ses sous-objects
     *
     * @param array $param
     * @param string $type
     * @return void
     */
    public function create(array $param, string $type = null) : void
	{
		$_SESSION[self::KEY][] = [self::CREATE . $type => $param];
	}

    /**
     * Ajoute à la pile la mise à jour d'un objet et ses sous-objects
     *
     * @param array $param
     * @param string $type
     * @return void
     */
	public function update(array $param, string $type = null) : void
	{
		$_SESSION[self::KEY][] = [self::UPDATE . $type => $param];
	}


    /**
     * Ajoute à la pile la suppréssion d'un objet et ses sous-objects
     *
     * @param array $param
     * @param string $type
     * @return void
     */
	public function delete(array $param, string $type = null) : void
	{
		$_SESSION[self::KEY][] = [self::DELETE . $type => $param];
	}


    /**
     * Ajoute à la pile une methode personnalisé d'un objet et ses sous-objects
     *
     * @param string $method
     * @param array $param
     * @param string $type
     * @return void
     */
	public function database(string $method, array $param, string $type = null) : void
	{
		$_SESSION[self::KEY][] = [$method . ':' . $type => $param];
	}
}