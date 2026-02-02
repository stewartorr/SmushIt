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
 * This uses resmush.it API: https://resmush.it/
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

define('SMUSHIT_MAX_FILE_SIZE', 5242880); // 5Mb maximum filesize set by resmushit

$base_path = MODX_CORE_PATH . 'components/smushit/';
$modx->addPackage('smushit', $base_path . 'model/');

if (!function_exists('smushitFormatBytes')) {
	function smushitFormatBytes(int $bytes, int $decimals = 2): string {
		$size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
		$factor = floor((strlen($bytes) - 1) / 3);
		$unit = $size[$factor] ?? 'B';
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $unit;
	}
}

if (!function_exists('smushitOptimisedFilename')) {
	function smushitOptimisedFilename(string $filename, bool $overwrite = false): string {

		// If we are overwriting the file, just return filename
		if ($overwrite) return $filename;

    $info = pathinfo($filename);

    // Extract name + extension
    $base = $info['filename'];
    $ext  = isset($info['extension']) ? strtolower($info['extension']) : '';

    // Slugify the base name
    $slug = strtolower($base);
    $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
    $slug = trim($slug, '-');

    if ($slug === '') {
        $slug = 'file';
    }

    return $slug . '.optimised' . ($ext ? ".$ext" : '');
	}
}

// Variables
$site_url = $modx->getOption('site_url');
$quality =  $modx->getOption('smushit.qlty') ?? 80 ;
$overwrite = false;

// If the image comes from a resized extra like pthumb or phpthumbof, overwrite the resized version
if (strpos($input, 'image-cache/') !== false ||
	strpos($input, 'phpthumbof/') !== false ||
	strpos($input, 'phpthumbsup/') !== false) {
    $overwrite = true;
}

// If image file is not blank and the image exists
if (!empty($input) && file_exists(MODX_BASE_PATH . $input)) {

	if ($smushit = $modx->getObject('modSmushit', ['src' => $input])) {
		// Check if image is same in case image was replaced
		if (filesize(MODX_BASE_PATH . $input) == $smushit->original) {
			return $smushit->dest;
		}
	}
	
	if (filesize(MODX_BASE_PATH . $input) > SMUSHIT_MAX_FILE_SIZE) {
		$modx->log(modX::LOG_LEVEL_ERROR, '[smushit] Filesize is larger than allowed 5Mb: ' . MODX_BASE_PATH . $input);
		return $input;
	}

	// Create new smushit image
	$smushit = $modx->newObject('modSmushit');

	// Form name for optimized file
	$dirs = explode('/', $input);
	$file = array_pop($dirs);
	$dest = implode('/', $dirs) . '/' . smushitOptimisedFilename($file, $overwrite);

	$mime = mime_content_type(MODX_BASE_PATH . $input) ?: 'application/octet-stream';
	$info = pathinfo($input);
	$name = $info['basename'];
	$output = new CURLFile(MODX_BASE_PATH . $input, $mime, $name);
	$data = array(
		"files" => $output,
	);

	// Start cURL request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.resmush.it/?qlty=' . $quality);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_USERAGENT, 'MODX Extra SmushIt/2.0.1 (https://extras.modx.com/package/smushit)');
	curl_setopt($ch, CURLOPT_REFERER, $site_url);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	$result = curl_exec($ch);
	if (curl_errno($ch)) {
		$modx->log(modX::LOG_LEVEL_ERROR, '[smushit] Could not optimise the image (cURL failure: ' . curl_error($ch) . ') ' . MODX_BASE_PATH . $input);
		return $input;
	}
	curl_close($ch);

	$image = json_decode($result);

	if (!is_object($image)) {
			$modx->log(modX::LOG_LEVEL_ERROR, '[smushit] Invalid JSON returned by resmush.it');
			return $input;
	}

	// If there is an error, report it
	if (isset($image->error)){
		$modx->log(modX::LOG_LEVEL_ERROR, '[smushit] Could not optimise image: ' . $site_url . $input);
		return $input;
	}

	// Save the remote image overwriting the original
	if (!@copy($image->dest, MODX_BASE_PATH . $dest)) {
		$modx->log(modX::LOG_LEVEL_ERROR, '[smushit] Could not save remote image: ' . $image->dest . ' -> ' . MODX_BASE_PATH . $dest);
		return $input;
	}

	// Get image data
	$original = $image->src_size;
	$optimised = $image->dest_size;
	
	// Set values
	$smushit->set('src', $input);
	$smushit->set('dest', $dest);
	$smushit->set('original', $original);
	$smushit->set('optimised', $optimised);
	$smushit->set('format', $mime);
	$smushit->set('smushdate', null);
	$smushit->save();

	// Log the savings
	$modx->log(modX::LOG_LEVEL_INFO, "[smushit] $input > Original " . smushitFormatBytes($original) . " vs. optimised " . smushitFormatBytes($optimised) . " " . number_format(100 - (($optimised/$original)*100), 2)  . "% saving.");
	return $dest;

} else {

	$modx->log(modX::LOG_LEVEL_ERROR, '[smushit] Something is wrong with the input image: ' . MODX_BASE_PATH . $input);
	return $input;

}