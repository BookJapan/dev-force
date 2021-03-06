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
$config = $this->GetConfigPage($page);

//	init
$error = null;

//	Check permit
if(!$this->CheckPermitAccount( $config, $name, $role, $error )){
	return false;
}

//	Get select config
$select = $this->GetSelectOfPage( $name, $role, $page, $error );
$record = $this->pdo()->select($select);

//	Get column name
$column_name_list = $this->GetColumnNameList( $record, $page );

//	Get page of description
$description = $this->GetPageDescription( $page );

//	column's struct
$column_struct = $this->GetColumnStruct($page);

$pkey = $config->pkey;
include('index.phtml');
