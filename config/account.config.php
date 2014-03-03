<?php
/* @var $this DevForce */

$config = new Config();

$name = 'root';
$pass = 'root';
$role = 'wheel';
$config->$name->pass = $pass;
$config->$name->role = $role;

$name = 'guest';
$pass = 'guest';
$role = 'guest';
$config->$name->pass = $pass;
$config->$name->role = $role;

$this->_config->{self::_CONFIG_ACCOUNT_}->merge($config);
