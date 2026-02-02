<?php
class SmushitRemoveProcessor extends modObjectProcessor {
    public $classKey = 'modSmushit';
    public $languageTopics = ['smushit:default'];
    public $objectType = 'smushit';

    public function initialize() {
        return parent::initialize();
    }

    public function process() {
        $id = (int) $this->getProperty('id');
        if (empty($id)) {
            return $this->failure($this->modx->lexicon('smushit.error_no_id'));
        }

        /** @var modSmushit $object */
        $object = $this->modx->getObject($this->classKey, $id);
        if (!$object) {
            return $this->failure($this->modx->lexicon('smushit.error_not_found'));
        }

        $src = MODX_BASE_PATH . $object->get('src');
        $dest = MODX_BASE_PATH . $object->get('dest');

        // Only delete the src filename destination
        if (file_exists($dest)) {
          @unlink($dest);
        }
        
        // Delete the database record
        $object->remove();

        return $this->success();
    }
}

return 'SmushitRemoveProcessor';
