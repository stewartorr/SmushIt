<?php

class SmushitGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modSmushit';
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
        return $c;
    }
}

return 'SmushitGetListProcessor';