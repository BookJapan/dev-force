<?php
/* @var $this DevForce */

$config = new Config();

//	page test count
$page_name = 'test-count';

//	The description of this page.
$config->$page_name->description = 'Simple test';

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

//	The description of this page.
$config->$page_name->description = 'Test of join table';

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

//	The description of this page.
$config->$page_name->description = 'Permit per column.';

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
$define->allow  = '';
$define->deny   = '';
$define->select = true;
$define->insert = true;
$define->update = false;
$define->delete = false;

$define->label = 'ID';
$config->$page_name->column->id = clone($define);
$config->$page_name->column->id->hidden = true;

$define->label = 'From';
$config->$page_name->column->source = clone($define);

$define->label = 'To';
$config->$page_name->column->target = clone($define);

$define->label = 'From 文章';
//$define->update = true;
$config->$page_name->column->text = clone($define);

$define->label = 'To 文章';
$config->$page_name->column->translation = clone($define);
//$config->$page_name->column->translation->update = true;
unset($config->$page_name->column->translation->update);
$config->$page_name->column->translation->update = 'root, guest';
//$config->$page_name->column->translation->update->allow = '';
//$config->$page_name->column->translation->update->deny  = '';

$define->label = 'タイムスタンプ';
$config->$page_name->column->timestamp = clone($define);

/*****************************************************************/

//	join
$this->_config->{self::_CONFIG_PAGE_} = $config;



