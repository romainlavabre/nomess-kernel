<?php

namespace NoMess\Core;

use NoMess\Core\Response;

interface ObserverInterface{

    public function alert(Response $instance) : void;

    public function collectData(Response $instance) : void;

}