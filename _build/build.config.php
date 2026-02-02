<?php
/**
 * Define the MODX path constants necessary for installation
 *
 * @package smushit
 * @subpackage build
 */

/* define version */
define('PKG_NAME','Smushit');
define('PKG_NAMESPACE', 'smushit');
define('PKG_NAME_LOWER', strtolower(PKG_NAME));
define('PKG_VERSION','2.0.1');
define('PKG_RELEASE','pl');

$root = dirname(dirname(__FILE__)).'/';
$sources = array (
    'root' => $root,
    'build' => $root .'_build/',
    'packages' => $root . '_packages/',
    'events' => $root . '_build/events/',
    'resolvers' => $root . '_build/resolvers/',
    'validators' => $root . '_build/validators/',
    'data' => $root . '_build/data/',
    'source_core' => $root.'core/components/'.PKG_NAME_LOWER,
    'source_assets' => $root.'assets/components/'.PKG_NAME_LOWER,
    'plugins' => $root.'core/components/'.PKG_NAME_LOWER.'/elements/plugins/',
    'snippets' => $root.'core/components/'.PKG_NAME_LOWER.'/elements/snippets/',
    'lexicon' => $root . 'core/components/'.PKG_NAME_LOWER.'/lexicon/',
    'docs' => $root.'core/components/'.PKG_NAME_LOWER.'/docs/',
    'model' => $root.'core/components/'.PKG_NAME_LOWER.'/model/',
    'schema_file' => $root.'core/components/'.PKG_NAME_LOWER.'/model/schema/smushit.mysql.schema.xml'
);
