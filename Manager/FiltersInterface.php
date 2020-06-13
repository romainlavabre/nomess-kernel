<?php


namespace NoMess\Manager;



use NoMess\Http\HttpRequest;
use NoMess\Http\HttpResponse;

interface FiltersInterface extends Distributor
{
    public function filtrate(HttpRequest $request, HttpResponse $response): void;
}