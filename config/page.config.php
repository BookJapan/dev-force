<?php
/* @var $this DevForce */

$config = new Config();

//	page test count
$page_name = 'test-count';

//	allow, deny
$config->$page_name->allow	 = '*';
$config->$page_name->deny	 = '';

//	
$config->$page_name->label	 = 'test(count)'; 
$config->$page_name->host	 = 'localhost'; 
$config->$page_name->database= 'test';
$config->$page_name->table	 = 't_count';
$config->$page_name->pkey	 = 'id';

/*****************************************************************/

//	page test test
$page_name = 'test-join';

//	allow, deny
$config->$page_name->allow	 = '*';
$config->$page_name->deny	 = '';

//
$config->$page_name->label	 = 'test(join)';
$config->$page_name->host	 = 'localhost';
$config->$page_name->database= 'test';
$config->$page_name->table	 = 't_join_base.id <= t_join_ex.id';
$config->$page_name->pkey	 = 't_join_base.id';

/*****************************************************************/

//	page test test
$page_name = 'onepiece-translate';

//	allow, deny
$config->$page_name->allow	 = '*';
$config->$page_name->deny	 = '';

//	table
$config->$page_name->label	 = 'translate';
$config->$page_name->host	 = 'localhost';
$config->$page_name->database= 'onepiece';
$config->$page_name->table	 = 'translate';
$config->$page_name->pkey	 = 'id';

//	column (single)
//$config->$page_name->column = 'source, target, text, translation, timestamp';

//	column (multi)
$define = new Config();
$define->select = true;
$define->insert = true;
$define->update = true;
$define->delete = false;

$define->label = 'From言語';
$config->$page_name->column->source = $define;

$define->label = 'To言語';
$config->$page_name->column->target = $define;

$define->label = 'From文章';
$config->$page_name->column->text = $define;

$define->label = 'To文章';
$define->update = false;
$config->$page_name->column->translation = $define;
 
$config->$page_name->column->timestamp = $define;

/*****************************************************************/

//	join
$this->_config->{self::_CONFIG_PAGE_} = $config;



