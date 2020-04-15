<?php

namespace NoMess\Database;


class Database
{


    /**
     * Ajoute à la pile la créetion d'un objet et ses sous-objects
     *
     * @param array $param
     * @param string $type
     * @return void
     */
    public function create(array $param, string $type = null) : void
	{
		$_SESSION['nomess_db'][] = ['create:' . $type => $param];
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
		$_SESSION['nomess_db'][] = ['update:' . $type => $param];
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
		$_SESSION['nomess_db'][] = ['delete:' . $type => $param];
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
		$_SESSION['nomess_db'][] = [$method . ':' . $type => $param];
	}
}