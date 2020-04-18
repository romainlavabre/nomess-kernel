<?php

namespace NoMess\Manager;

use NoMess\HttpRequest\HttpRequest;
use NoMess\HttpResponse\HttpResponse;


abstract class ControllerManager{

	abstract function doGet(HttpResponse $response, HttpRequest $request) : void;

	abstract function doPost(HttpResponse $response, HttpRequest $request) : void;

}
