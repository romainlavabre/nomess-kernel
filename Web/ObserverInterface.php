<?php

namespace NoMess\Web;

use NoMess\HttpResponse\HttpResponse;


interface ObserverInterface{

    public function alert(HttpResponse $instance) : void;

    public function collectData(HttpResponse $instance) : void;

}