<?php

$Module = array( "name" => "ChatGPT");

$ViewList = array();

$ViewList['settings'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure'),
);

$ViewList['settingssuggest'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure'),
);

$ViewList['index'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure'),
);

$ViewList['suggest'] = array(
    'params' => array(),
    'uparams' => array('id','chat'),
    'functions' => array('auto_suggester'),
    'multiple_arguments' => array ( 'id' )
);

$ViewList['suggestinvalid'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('auto_suggester')
);

$ViewList['invalid'] = array(
    'params' => array(),
    'uparams' => array('reviewed'),
    'functions' => array('manage_invalid')
);

$ViewList['test'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure')
);

$ViewList['getanswer'] = array(
    'params' => array('action'),
    'uparams' => array(),
    'functions' => array('tab_suggester')
);

$ViewList['getanswerparams'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure')
);

$ViewList['delete'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('manage_invalid')
);

$FunctionList['manage_invalid'] = array('explain' => 'Allow operator to manage invalid reports');
$FunctionList['configure'] = array('explain' => 'Allow operator to configure ChatGPT');
$FunctionList['auto_suggester'] = array('explain' => 'Enable auto suggester for the operator');
$FunctionList['tab_suggester'] = array('explain' => 'Allow to use chat tab suggester in chat window');