<?php

$fieldsSearch = array();

$fieldsSearch['sort'] = array (
    'type' => 'text',
    'trans' => 'Sort',
    'required' => false,
    'valid_if_filled' => false,
    'filter_type' => false,
    'validation_definition' => new ezcInputFormDefinitionElement (
        ezcInputFormDefinitionElement::OPTIONAL, 'string'
    )
);

$fieldsSearch['reviewed'] = array (
    'type' => 'text',
    'trans' => 'Username',
    'required' => false,
    'valid_if_filled' => false,
    'filter_type' => 'filter',
    'filter_table_field' => 'reviewed',
    'validation_definition' => new ezcInputFormDefinitionElement (
        ezcInputFormDefinitionElement::OPTIONAL, 'int' , array('min_range' => 0)
    )
);

$fieldSortAttr = array (
    'field'      => 'sort',
    'default'    => 'newfirst',
    'sort_column' => 'id',
    'options'    => array(
        'newfirst' => array('sort_column' => '`id` DESC'),
        'oldfirst' => array('sort_column' => '`id` ASC')
    )
);

return array(
    'filterAttributes' => $fieldsSearch,
    'sortAttributes'   => $fieldSortAttr
);