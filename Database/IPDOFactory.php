<?php

namespace NoMess\Database;

interface IPDOFactory
{
    public function getConnection() : \PDO;
} 