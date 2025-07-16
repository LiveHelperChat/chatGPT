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

$ViewList['newcrawl'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('configure'),
);

$ViewList['editcrawl'] = array(
    'params' => array('vector_storage_id','crawl_id'),
    'uparams' => array(),
    'functions' => array('configure'),
);

$ViewList['deletecrawl'] = array(
    'params' => array('vector_storage_id','crawl_id'),
    'uparams' => array('csfr'),
    'functions' => array('configure'),
);

$ViewList['fetchpendingcrawls'] = array(
    'params' => array(),
    'uparams' => array()
);

$ViewList['updatecrawlstatus'] = array(
    'params' => array(),
    'functions' => array()
);

$ViewList['resetcrawl'] = array(
    'params' => array('storage_id', 'crawl_id'),
    'uparams' => array('csfr'),
    'functions' => array('configure'),
);

$ViewList['processcontent'] = array(
    'params' => array('storage_id', 'crawl_id'),
    'uparams' => array('csfr'),
    'functions' => array('configure'),
);

$FunctionList['configure'] = array('explain' => 'Allow operator to configure ChatGPT Vector storage');

