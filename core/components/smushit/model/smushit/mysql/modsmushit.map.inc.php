<?php

$xpdo_meta_map['modSmushit'] = array (
  'package' => 'smushit',
  'version' => '1.1',
  'table' => 'smushit',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
      'engine' => 'InnoDB',
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
          'dbtype' => 'varchar',
          'precision' => '255',
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
          'phptype' => 'integer',
          'null' => true,
      ),
      'optimised' => 
      array (
          'dbtype' => 'int',
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
          'null' => true,
          'default' => 'CURRENT_TIMESTAMP',
      ),
  ),
  'indexes' => 
  array (
      'src' => 
      array (
          'alias' => 'src',
          'primary' => false,
          'unique' => false,
          'type' => 'BTREE',
          'columns' => 
          array (
              'src' => 
              array (
                  'length' => '255',
              ),
          ),
      ),
  ),
);
