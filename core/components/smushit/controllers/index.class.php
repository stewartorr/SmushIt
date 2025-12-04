<?php
require_once dirname(dirname(__FILE__)) . '/model/smushit/smushit.class.php';

use Smushit;

class SmushitIndexManagerController extends modExtraManagerController
{
  /** @var Smushit $smushit */
  public $smushit;
  public function initialize()
  {
    $this->smushit = new Smushit($this->modx);
    // $this->addCss($this->smushit->config['cssUrl'].'mgr.css');
    $this->addJavascript($this->smushit->config['jsUrl'] . 'mgr/smushit.js');
    $this->addHtml('<script type="text/javascript">
      Ext.onReady(function() {
          Smushit.config = ' . $this->modx->toJSON($this->smushit->config) . ';
      });
      </script>');
    return parent::initialize();
  }
  public function getLanguageTopics()
  {
    return array('smushit:default');
  }
  public function checkPermissions()
  {
    return true;
  }
  public function process(array $scriptProperties = array()) {}
  public function getPageTitle()
  {
    return $this->modx->lexicon('smushit');
  }
  public function loadCustomCssJs()
  {
    $this->addJavascript($this->smushit->config['jsUrl'] . 'mgr/widgets/smushit.grid.js');
    $this->addJavascript($this->smushit->config['jsUrl'] . 'mgr/widgets/home.panel.js');
    $this->addLastJavascript($this->smushit->config['jsUrl'] . 'mgr/sections/index.js');
  }
  public function getTemplateFile()
  {
    return $this->smushit->config['templatesPath'] . 'home.tpl';
  }
}
