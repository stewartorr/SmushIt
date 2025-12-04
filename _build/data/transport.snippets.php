<?php
/**
 * @package smushit
 * @subpackage build
 */

function getSnippetContent($filename = '') {
    $o = file_get_contents($filename);
    $o = str_replace('<?php','',$o);
    $o = str_replace('?>','',$o);
    $o = trim($o);
    return $o;
}

$snippets = array();

/* course snippets */
$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'smushit',
    'description' => 'This is an output filter that processes images with https://resmush.it/api in an attempt to reduce filesizes. Created by Stewart Orr https://www.stewartorr.co.uk/smushit/',
    'snippet' => getSnippetContent($sources['snippets'] . 'snippet.smushit.php'),
    ), '', true, true);

return $snippets;