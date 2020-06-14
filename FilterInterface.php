<?php


namespace NoMess;


use NoMess\Http\HttpRequest;
use NoMess\Http\HttpResponse;

interface FilterInterface
{
    public function filtrate(HttpRequest $request, HttpResponse $response): void;
}