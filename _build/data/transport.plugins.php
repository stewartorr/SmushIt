<?php
/**
 * @package smushit
 * @subpackage build
 */

function getPluginContent($filename = '') {
    $o = file_get_contents($filename);
    $o = str_replace('<?php','',$o);
    $o = str_replace('?>','',$o);
    $o = trim($o);
    return $o;
}

$plugins = array();

/* create the plugin object */
$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->set('id', 1);
$plugins[0]->set('name', 'smushitCacheManager');
$plugins[0]->set('description', 'Handles cache cleaning when clearing the Site Cache.');
$plugins[0]->set('plugincode', getPluginContent($sources['plugins'] . 'plugin.smushitCacheManager.php'));
$plugins[0]->set('category', 0);

// $events = include $sources['events'].'events.clientconfig.php';
// if (is_array($events) && !empty($events)) {
//     $plugins[0]->addMany($events);
//     $modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($events).' Plugin Events for ClientConfig.'); flush();
// } else {
//     $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find plugin events for ClientConfig!');
// }
// unset($events);

return $plugins;