<?php

namespace NoMess;

interface SubjectInterface
{
    public function notify() : void;

    public function attach() : void;
}