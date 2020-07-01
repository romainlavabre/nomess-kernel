<?php


namespace Nomess;


use NoMess\Http\HttpRequest;
use NoMess\Http\HttpResponse;

interface FilterInterface
{
    public function filtrate(): void;
}
