<?php
/**
 * Build Schema script
 *
 * @package smushit
 * @subpackage build
 */

require_once dirname(dirname(__FILE__)) . '/config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
require_once 'build.config.php';

$modx= new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

$manager = $modx->getManager();
$generator = $manager->getGenerator();

if (!is_dir($sources['model'])) {
    $modx->log(modX::LOG_LEVEL_ERROR,'Model directory not found: ' . $sources['model']);
    exit;
}
if (!file_exists($sources['schema_file'])) { 
    $modx->log(modX::LOG_LEVEL_ERROR,'Schema file not found: ' . $sources['schema_file']);
    exit;
}

$generator->parseSchema($sources['schema_file'], $sources['model']);
$modx->addPackage(PKG_NAME_LOWER, $sources['model']);
$manager->createObjectContainer('smushit');

$modx->log(modX::LOG_LEVEL_INFO, 'Done!');