<?php


namespace NoMess\Manager;


use NoMess\HttpRequest\HttpRequest;
use NoMess\HttpResponse\HttpResponse;

interface FiltersInterface extends Distributor
{
    public function filtrate(HttpRequest $request, HttpResponse $response): void;
}