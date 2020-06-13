<?php

if(!opcache_reset()){
    echo 'Sorry, opcache is disabled';
}