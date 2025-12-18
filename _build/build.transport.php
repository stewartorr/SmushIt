<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
/**
 * SmushIt build script
 *
 * @package smushit
 * @subpackage build
 */

$tstart = explode(' ', microtime());
$tstart = $tstart[1] + $tstart[0];
set_time_limit(0);
echo "<pre>";

require_once dirname(dirname(__FILE__)) . '/config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
require_once 'build.config.php';

$modx = new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

// Be sure to use MODX 2.x to build the package for cross compatability. If built with 3.x, the installer will only work on 3.x.
$builder = class_exists(\MODX\Revolution\Transport\modPackageBuilder::class) 
    ? new \MODX\Revolution\Transport\modPackageBuilder($modx) : new \modPackageBuilder($modx);

$builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);
$builder->registerNamespace(
    PKG_NAMESPACE,
    false,
    true,
    '{core_path}components/' . PKG_NAMESPACE . '/',
    '{assets_path}components/' . PKG_NAMESPACE . '/',
);
$modx->getService('lexicon', 'modLexicon');

/*------------------------------------------------------------------------------
> Requirements script
------------------------------------------------------------------------------*/

$builder->package->put(
    [
        'source' => $sources['source_core'],
        'target' => "return MODX_CORE_PATH . 'components/';",
    ],
    [
        xPDOTransport::ABORT_INSTALL_ON_VEHICLE_FAIL => true,
        'vehicle_class' => 'xPDO\Transport\xPDOFileVehicle',
        'validate' => [
            [
                'type' => 'php',
                'source' => $sources['validators'] . 'requirements.script.php'
            ],
        ],
    ],
);

/*------------------------------------------------------------------------------
    Create Category
------------------------------------------------------------------------------*/

/* create category */
$category= $modx->newObject('modCategory');
$category->set('id',1);
$category->set('category', PKG_NAME);

/*------------------------------------------------------------------------------
    Snippets
------------------------------------------------------------------------------*/

$snippets = include $sources['data'] . 'transport.snippets.php';
if (empty($snippets)) $modx->log(modX::LOG_LEVEL_ERROR, 'Could not package in snippets.');
$category->addMany($snippets);
$modx->log(modX::LOG_LEVEL_INFO, '✅ Packaged in ' . count($snippets).' snippets.'); flush();

/*------------------------------------------------------------------------------
    Plugins
------------------------------------------------------------------------------*/

$plugins = include $sources['data'] . 'transport.plugins.php';
if (!is_array($plugins)) { $modx->log(modX::LOG_LEVEL_FATAL, 'Adding plugins failed.'); }
$attributes= array(
    xPDOTransport::UNIQUE_KEY => 'name',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'PluginEvents' => array(
            xPDOTransport::PRESERVE_KEYS => true,
            xPDOTransport::UPDATE_OBJECT => false,
            xPDOTransport::UNIQUE_KEY => array('pluginid','event'),
        ),
    ),
);

foreach ($plugins as $plugin) {
    $vehicle = $builder->createVehicle($plugin, $attributes);
    $builder->putVehicle($vehicle);
}
$category->addMany($plugins);
$modx->log(modX::LOG_LEVEL_INFO, '✅ Packaged in ' . count($plugins).' plugins.'); flush();
unset($plugins, $plugin, $attributes);

/*------------------------------------------------------------------------------
    Events
------------------------------------------------------------------------------*/

$events = include $sources['data'] . 'transport.events.php';
$attr =  array(
    xPDOTransport::UNIQUE_KEY => 'name',
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
);
foreach ($events as $e) {
    $vehicle = $builder->createVehicle($e, $attr);
    $builder->putVehicle($vehicle);
}
$modx->log(modX::LOG_LEVEL_INFO, '✅ Packaged in ' . count($events).' events.'); flush();

/*------------------------------------------------------------------------------
    Category
------------------------------------------------------------------------------*/

// Category Vehicle
$attr = [
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => false,
];
$vehicle = $builder->createVehicle($category, $attr);
$vehicle->resolve('php', [
    'source' => $sources['resolvers'] . 'tables.resolver.php',
]);
$modx->log(modX::LOG_LEVEL_INFO, '✅ Packaged in resolvers.');
flush();
$builder->putVehicle($vehicle);

/*------------------------------------------------------------------------------
    Load Menu
------------------------------------------------------------------------------*/

$modx->log(modX::LOG_LEVEL_INFO,'Packaging in menu...');
$menu = include $sources['data'].'transport.menu.php';
if (empty($menu)) $modx->log(modX::LOG_LEVEL_ERROR, 'Could not package in menu.');

$vehicle= $builder->createVehicle($menu,array (
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::UNIQUE_KEY => 'text',
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'Action' => array (
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => array ('namespace', 'controller'),
        ),
    ),
));
$modx->log(modX::LOG_LEVEL_INFO, '✅ Packaged in menu.');

/*------------------------------------------------------------------------------
    Settings
------------------------------------------------------------------------------*/

$settings = array();
include_once $sources['data'].'transport.settings.php';
$attributes= array(
    xPDOTransport::UNIQUE_KEY => 'key',
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
);
foreach ($settings as $setting) {
    $vehicle = $builder->createVehicle($setting,$attributes);
    $builder->putVehicle($vehicle);
}
$modx->log(modX::LOG_LEVEL_INFO, '✅ Packaged in settings.');
unset($settings,$setting,$attributes);

/*------------------------------------------------------------------------------
    Licence, Readme & Setup
------------------------------------------------------------------------------*/

$modx->log(modX::LOG_LEVEL_INFO,'Adding package attributes and setup options...');
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
    'setup-options' => array(
        'source' => $sources['build'].'setup.options.php',
    ),
));
$modx->log(modX::LOG_LEVEL_INFO, '✅ Packaged in package attributes.');
flush();

/*------------------------------------------------------------------------------
    Zip up package
------------------------------------------------------------------------------*/

$modx->log(modX::LOG_LEVEL_INFO,'Packing up transport package zip...');
$builder->pack();

$tend= explode(" ", microtime());
$tend= $tend[1] + $tend[0];
$totalTime= sprintf("%2.4f s",($tend - $tstart));

$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

echo "</pre>";
