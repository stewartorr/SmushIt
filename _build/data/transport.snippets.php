<?php
/**
 * @package smushit
 * @subpackage build
 */

function getSnippetContent($filename = '') {
    $o = file_get_contents($filename);
    $o = str_replace(['<?php', '?>'], '', $o);
    return trim($o);
}

$snippets = [];
$snippet = $modx->newObject('modSnippet');
$snippet->fromArray([
    'name'        => 'smushit',
    'description' => 'Processes images using https://resmush.it/api to reduce file sizes.',
    'snippet'     => getSnippetContent($sources['snippets'] . 'snippet.smushit.php'),
], '', true, true);
$snippets[] = $snippet;

return $snippets;
