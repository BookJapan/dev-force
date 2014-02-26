<?php
/* @var $this DevForce */

//  .htaccess file has not been initialized.
if( empty($this) ){
	include('./zlib/template/introduction.phtml');
	return;
}

//	Get login id
if(!$id = $this->model('Login')->GetLoginID()){
	include('login.php'); // Does not exists.
	return;
}

//	Separate to name and role
list( $name, $role ) = explode(':',$id);

//  Get Action
$action = $this->GetAction();

//  Get URL Args
$page = $this->GetArgs('page');
//$this->mark('page='.$page);
$config = $this->GetConfigPage($page);
//$this->d($config);

//	Check permit
if(!$this->CheckPermit( $config, $name, $role )){
	return false;
}

//	Get select config
$select = $this->GetSelectOfPage( $name, $role, $page, $error );
$record = $this->pdo()->select($select);
//$this->d($record);

include('index.phtml');
