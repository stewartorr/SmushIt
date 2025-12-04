<?php
$xpdo_meta_map['Smushit']= array (
  'package' => 'smushit',
  'version' => '1.1',
  'table' => 'smushit',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'MyISAM',
  ),
  'fields' => 
  array (
    'src' => NULL,
    'dest' => NULL,
    'original' => NULL,
    'optimised' => NULL,
    'format' => NULL,
    'smushdate' => 'CURRENT_TIMESTAMP',
  ),
  'fieldMeta' => 
  array (
    'src' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'dest' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'original' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
    ),
    'optimised' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
    ),
    'format' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '10',
      'phptype' => 'string',
      'null' => true,
    ),
    'smushdate' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'default' => 'CURRENT_TIMESTAMP',
    ),
  ),
);
