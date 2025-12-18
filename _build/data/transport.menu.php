<?php
/**
 * Adds modMenu into package
 *
 * @package smushit
 * @subpackage build
 */

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