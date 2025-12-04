<?php
/**
 * @package smushit
 * @subpackage build
 */
$settings = array();

$settings['smushit.qlty1']= $modx->newObject('modSystemSetting');
$settings['smushit.qlty1']->fromArray(array(
    'key' => 'smushit.qlty',
    'value' => '80',
    'xtype' => 'textfield',
    'namespace' => 'smushit',
    'area' => 'SmushIt',
    'lexicon' => 'smushit.qlty',
    'description' => 'Default JPEG quality setting (recomended value 80)'
),'',true,true);

return $settings;