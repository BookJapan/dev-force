<?php
/* @var $this DevForce */

$config = new Config();

//	page test count
$page_name = 'test-count';

//	allow, deny
$config->$page_name->allow	 = '*';
$config->$page_name->deny	 = '';

//	
$config->$page_name->host	 = 'localhost'; 
$config->$page_name->database= 'test';
$config->$page_name->table	 = 't_count';
$config->$page_name->pkey	 = 'id';

//	page test test
$page_name = 'test-test';

//	allow, deny
$config->$page_name->allow	 = '*';
$config->$page_name->deny	 = '';

//
$config->$page_name->host	 = 'localhost';
$config->$page_name->database= 'test';
$config->$page_name->table	 = 't_test_base.id <= t_test_ex.id';
$config->$page_name->pkey	 = 't_test_base.id';

//	join
$this->_config->{self::_CONFIG_PAGE_} = $config;
