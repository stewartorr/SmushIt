<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
class SmushitGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'Smushit';
    public $languageTopics = array('smushit:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'smushit';
    public function initialize() {
    $this->modx->addPackage(
        'smushit',
        $this->modx->getOption('smushit.core_path', null,
            $this->modx->getOption('core_path').'components/smushit/'
        ) . 'model/'
    );
    return parent::initialize();
}
    public function prepareRow(xPDOObject $object) {
        return $object->toArray();
    }
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'src:LIKE' => '%'.$query.'%'
            ));
        }
        $this->modx->log(modX::LOG_LEVEL_ERROR, '[Smushit] SQL: ' . $c->toSQL());
        return $c;
    }
}
return 'SmushitGetListProcessor';