<?php

$Module = array( "name" => "ChatGPT Vector Storage");

$ViewList = array();

$ViewList['new'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure'),
);

$ViewList['list'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure'),
);

$ViewList['delete'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('configure'),
);

$ViewList['deletefile'] = array(
    'params' => array('vector_storage_id','file_id'),
    'uparams' => array('csfr'),
    'functions' => array('configure'),
);

$ViewList['view'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('configure'),
);

$ViewList['upload'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('configure'),
);

$ViewList['edit'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure'),
);

$FunctionList['configure'] = array('explain' => 'Allow operator to configure ChatGPT Vector storage');
