<?php

namespace NoMess;

interface ObserverInterface
{
    public function notifiedInput() : void;

    public function notifiedOutput() : void;
}

//
