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

$tstart = microtime(true);
set_time_limit(0);
echo "<pre>";

require_once dirname(dirname(__FILE__)) . '/config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
require_once 'build.config.php';

$modx = new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

/*------------------------------------------------------------------------------
    Package Builder
------------------------------------------------------------------------------*/

$builder = class_exists(\MODX\Revolution\Transport\modPackageBuilder::class)
    ? new \MODX\Revolution\Transport\modPackageBuilder($modx)
    : new \modPackageBuilder($modx);

$builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);
$builder->registerNamespace(
    PKG_NAMESPACE,
    false,
    true,
    '{core_path}components/' . PKG_NAMESPACE . '/',
    '{assets_path}components/' . PKG_NAMESPACE . '/'
);

$modx->getService('lexicon', 'modLexicon');

/*------------------------------------------------------------------------------
    Requirements
------------------------------------------------------------------------------*/

$builder->package->put(
    [
        'source' => $sources['source_core'],
        'target' => "return MODX_CORE_PATH . 'components/';",
    ],
    [
        xPDOTransport::ABORT_INSTALL_ON_VEHICLE_FAIL => true,
        'vehicle_class' => 'xPDO\\Transport\\xPDOFileVehicle',
        'validate' => [
            [
                'type' => 'php',
                'source' => $sources['validators'] . 'requirements.script.php',
            ],
        ],
    ]
);

/*------------------------------------------------------------------------------
    Category
------------------------------------------------------------------------------*/

$category = $modx->newObject('modCategory');
$category->set('category', PKG_NAME);

/*------------------------------------------------------------------------------
    Snippets (owned by category)
------------------------------------------------------------------------------*/

$snippets = include $sources['data'] . 'transport.snippets.php';
if (empty($snippets)) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'No snippets found.');
} else {
    $category->addMany($snippets);
    $modx->log(modX::LOG_LEVEL_INFO, '✅ Added ' . count($snippets) . ' snippets.');
}

/*------------------------------------------------------------------------------
    Category Vehicle (THIS installs snippets correctly)
------------------------------------------------------------------------------*/

$categoryAttributes = [
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => [
        'Snippets' => [
            xPDOTransport::UNIQUE_KEY => 'name',
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
        ],
    ],
];

$categoryVehicle = $builder->createVehicle($category, $categoryAttributes);
$categoryVehicle->resolve('php', [
    'source' => $sources['resolvers'] . 'tables.resolver.php',
]);
$builder->putVehicle($categoryVehicle);

/*------------------------------------------------------------------------------
    Menu
------------------------------------------------------------------------------*/

$menu = include $sources['data'] . 'transport.menu.php';
if (!empty($menu)) {
    foreach ($menu as $m) {
        $menuObject = $modx->newObject('modMenu');
        $menuObject->fromArray($m, '', true, true);

        $menuVehicle = $builder->createVehicle($menuObject, [
            xPDOTransport::UNIQUE_KEY => 'text',
            xPDOTransport::PRESERVE_KEYS => true,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::RELATED_OBJECTS => true,
            xPDOTransport::RELATED_OBJECT_ATTRIBUTES => [
                'Action' => [
                    xPDOTransport::UNIQUE_KEY => ['namespace', 'controller'],
                    xPDOTransport::PRESERVE_KEYS => false,
                    xPDOTransport::UPDATE_OBJECT => true,
                ],
            ],
        ]);

        $builder->putVehicle($menuVehicle);
    }
}

/*------------------------------------------------------------------------------
    Settings
------------------------------------------------------------------------------*/

$settings = [];
include $sources['data'] . 'transport.settings.php';

foreach ($settings as $setting) {
    $vehicle = $builder->createVehicle($setting, [
        xPDOTransport::UNIQUE_KEY => 'key',
        xPDOTransport::PRESERVE_KEYS => true,
        xPDOTransport::UPDATE_OBJECT => false,
    ]);
    $builder->putVehicle($vehicle);
}

/*------------------------------------------------------------------------------
    Package Attributes
------------------------------------------------------------------------------*/

$builder->setPackageAttributes([
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
    'setup-options' => [
        'source' => $sources['build'] . 'setup.options.php',
    ],
]);

/*------------------------------------------------------------------------------
    Build Package
------------------------------------------------------------------------------*/

$builder->pack();

$modx->log(
    modX::LOG_LEVEL_INFO,
    'Package built in ' . round(microtime(true) - $tstart, 2) . 's'
);

echo "</pre>";
