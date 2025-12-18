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
$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->set('id', 1);
$plugins[0]->set('name', 'smushitCacheManager');
$plugins[0]->set('description', 'Handles cache cleaning when clearing the Site Cache.');
$plugins[0]->set('plugincode', getPluginContent($sources['plugins'] . 'plugin.smushitCacheManager.php'));
return $plugins;