<?php

use MODX\Revolution\modX;

class Smushit
{
  /** @var modX $modx */
  public $modx;
  public $namespace = 'Smushit';

  public function __construct(modX &$modx, array $config = [])
  {
    $this->modx = &$modx;

    $basePath = $this->modx->getOption('smushit.core_path',$config,$this->modx->getOption('core_path').'components/smushit/');
    $assetsUrl = $this->modx->getOption('smushit.assets_url',$config,$this->modx->getOption('assets_url').'components/smushit/');

    $this->config = array_merge(array(
      'basePath' => $basePath,
      'corePath' => $basePath,
      'modelPath' => $basePath.'model/',
      'processorsPath' => $basePath.'processors/',
      'templatesPath' => $basePath.'templates/',
      'chunksPath' => $basePath.'elements/chunks/',
      'jsUrl' => $assetsUrl.'js/',
      'cssUrl' => $assetsUrl.'css/',
      'assetsUrl' => $assetsUrl,
      'connectorUrl' => $assetsUrl.'connector.php',
    ),$config);
    $this->modx->addPackage('smushit', $this->config['modelPath']);
  }
}
