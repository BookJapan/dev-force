<?php
/* @var $this DevForce */

$config = new Config();

//	test page
$page_name = 'test';

//	allow, deny
$config->$page_name->allow	 = '*';
$config->$page_name->deny	 = '';

//	
$config->$page_name->host	 = 'localhost'; 
$config->$page_name->database= 'test';
$config->$page_name->table	 = 't_count';
$config->$page_name->pkey	 = 'id';

//	test page select
$select = new Config();
$select->database = 'test';
$select->table = 't_count';
$config->$page_name->select->default = $select;

//	join
$this->_config->{self::_CONFIG_PAGE_} = $config;
