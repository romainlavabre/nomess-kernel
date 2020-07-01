<?php


namespace Nomess\Manager;



use Nomess\Http\HttpRequest;
use Nomess\Http\HttpResponse;

interface FiltersInterface
{
    public function filtrate(HttpRequest $request, HttpResponse $response): void;
}
