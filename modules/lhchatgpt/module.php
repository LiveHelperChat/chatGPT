<?php

$Module = array( "name" => "ChatGPT");

$ViewList = array();

$ViewList['settings'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure'),
);

$FunctionList['configure'] = array('explain' => 'Allow operator to configure ChatGPT');