<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lhc_chatgpt_crawl";
$def->class = '\LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl';

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

foreach (['crawl_frequency','last_crawled_at','status', 'number_of_pages', 'max_pages', 'lhc_file_id','type'] as $posAttr) {
    $def->properties[$posAttr] = new ezcPersistentObjectProperty();
    $def->properties[$posAttr]->columnName   = $posAttr;
    $def->properties[$posAttr]->propertyName = $posAttr;
    $def->properties[$posAttr]->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
}

foreach (['name','vector_storage_id', 'url', 'file_id', 'base_url', 'start_url','content'] as $posAttr) {
    $def->properties[$posAttr] = new ezcPersistentObjectProperty();
    $def->properties[$posAttr]->columnName   = $posAttr;
    $def->properties[$posAttr]->propertyName = $posAttr;
    $def->properties[$posAttr]->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;
}

return $def;

?>