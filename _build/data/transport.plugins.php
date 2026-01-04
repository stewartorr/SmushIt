<?php
/**
 * @package smushit
 * @subpackage build
 */

function getPluginContent($filename) {
    $content = file_get_contents($filename);
    $content = preg_replace('/^<\?php/', '', $content);
    $content = preg_replace('/\?>$/', '', $content);
    return trim($content);
}

$plugins = [];
$plugin = $modx->newObject('modPlugin');
$plugin->set('name', 'smushitCacheManager');
$plugin->set('description', 'Handles cache cleaning when clearing the Site Cache.');
$plugin->set('plugincode', getPluginContent($sources['plugins'] . 'plugin.smushitCacheManager.php'));
$plugins[] = $plugin;

return $plugins;
