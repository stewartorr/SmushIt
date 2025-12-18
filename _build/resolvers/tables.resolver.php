<?php
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_UPGRADE:
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption(
                'versionx.core_path',
                null,
                $modx->getOption('core_path') . 'components/smushit/'
                ) . 'model/';
            $modx->addPackage('smushit', $modelPath);
            $manager = $modx->getManager();
            $manager->createObjectContainer('modSmushit');
        break;
    }
} else {
    die("⚠️ An error occurred when trying to package tables.resolver.php");
}
return true;