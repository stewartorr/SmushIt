<?php
/**
 * smushit
 *
 * This extra tries to optimise and compress PNG and JPEG images for better
 * performance using the resmush.it optimisation API. This will help massively
 * with Google Page Speed and can reduce image sizes by up to 70%. This will
 * overwrite any existing images so is intended to be used as an output filter
 * after pthumb or similar.
 *
 * This uses resmush.it API: http://resmush.it/
 *
 * smushit is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * smushit is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.

 * @author Stewart Orr <stewart.orr@gmail.com>
 * @version 2.0
 * @copyright Copyright Stewart Orr 2025
 */

$base_path = MODX_CORE_PATH . 'components/smushit/';
$modx->addPackage('smushit', $base_path.'model/');

if ($modx->event->name === 'OnSiteRefresh') {
    $modx->log(modX::LOG_LEVEL_INFO,'[smushit]: Clearing cache of images.');
    $modx->exec("TRUNCATE TABLE {$modx->getTableName('smushit')}");
}