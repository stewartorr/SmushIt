<?php
/**
 * Resolve creating custom db tables during install.
 *
 * @package smushit
 * @subpackage build
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:

            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('core_path').'components/smushit/model/';
            $modx->addPackage('smushit', $modelPath);
            $manager = $modx->getManager();
            $manager->createObjectContainer('smushit');
            break;

        case xPDOTransport::ACTION_UPGRADE:
            break;
    }
}
return true;