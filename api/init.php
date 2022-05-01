<?php 

// load all class
spl_autoload_register(function($class){
    require_once "config/" . $class. ".php";
});

$user = new User();