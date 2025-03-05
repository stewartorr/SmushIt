<?php
/**
 * smushit
 *
 * This snippet tries to optimise and compress PNG and JPEG images for better 
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

 * @author Stewart Orr @ Qodo Ltd <stewart@qodo.co.uk>
 * @version 1.0
 * @copyright Copyright Qodo Ltd 2016
 */

if (!function_exists('smushitFormatBytes')) {
    function smushitFormatBytes($bytes, $decimals = 2) {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }
}

$input = ltrim($input, '/');

// Form name for optimized file
$dirs = explode('/', $input);
$file = array_pop($dirs);
$output = implode('/', $dirs) . '/op-' . $file;

// If image file is not blank and the image exists
if (!empty($input) && file_exists(MODX_BASE_PATH . $input)) {
    // If optimazed file not exists
    if (!file_exists(MODX_BASE_PATH . $output)) {
        // Variables
        $site_url = rtrim($modx->getOption('site_url'), '/');
        $image = json_decode(file_get_contents('http://api.resmush.it/ws.php?img=' . $site_url . '/' . $input));
        $original = filesize(MODX_BASE_PATH . $input);
        
        // If there is an error, report it
        if (isset($image->error)){
            $modx->log(modX::LOG_LEVEL_ERROR, '[smushit] Could not optimise image: ' . $site_url . '/'  . $input);
            return $input;
        }
    
        // Save the remote image overwriting the original
        copy($image->dest, MODX_BASE_PATH . $output) or die("Could not save remote image.");
        
        // Get optimised image filesize
        $optimised = filesize(MODX_BASE_PATH . $output);
            
        // Log the savings
        $modx->log(modX::LOG_LEVEL_DEBUG, "[smushit] $input > Original " . smushitFormatBytes($original) . " vs. optimised " . smushitFormatBytes($optimised) . " " . number_format(100 - (($optimised/$original)*100), 2)  . "% saving.");
    }
    return $output;
    
} else {
    
    $modx->log(modX::LOG_LEVEL_ERROR, '[smushit] Something is wrong with the input image: ' . MODX_BASE_PATH . $input);
    // Return whatever was provided
    return $input;
    
}
