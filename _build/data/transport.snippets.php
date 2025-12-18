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
$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->set('id', 1);
$snippets[1]->set('name', 'smushit');
$snippets[1]->set('description', 'This is an output filter that processes images with https://resmush.it/api in an attempt to reduce filesizes. Created by Stewart Orr https://www.stewartorr.co.uk/smushit/');
$snippets[1]->set('snippet', getSnippetContent($sources['snippets'] . 'snippet.smushit.php'));
return $snippets;