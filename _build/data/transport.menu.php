<?php
/**
 * Adds modActions and modMenus into package
 *
 * @package smushit
 * @subpackage build
 */
// $action= $modx->newObject('modAction');
// $action->fromArray(array(
//     'id' => 1,
//     'namespace' => 'smushit',
//     'parent' => 0,
//     'controller' => 'controllers/index',
//     'haslayout' => true,
//     'lang_topics' => 'smushit:default',
//     'assets' => '',
// ),'',true,true);

$menu = $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => 'smushit',
    'parent' => 'components',
    'description' => 'smushit.desc',
    'action' => 'index',
    'namespace' => 'smushit',
    'menuindex' => 0,
    'params' => '',
    'handler' => '',
),'',true,true);

$vehicle = $builder->createVehicle($menu, array (
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::UNIQUE_KEY => 'text',
    xPDOTransport::RELATED_OBJECTS => false,
));

$builder->putVehicle($vehicle);
unset ($vehicle,$childActions,$action,$menu);