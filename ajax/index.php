<?php
/* @var $this DevForce */
$st = microtime(true);

//	Do not display layout.
$this->SetEnv('layout',false);

//	Set mime
$this->SetEnv('mime','text/javascript');

//	Get name, role (Login check is DevForce::Dispatch)
$id = $this->model('Login')->GetLoginID();
list( $name, $role ) = explode(':',$id);

$status	 = 0;
$error	 = '';
$dml	 = Toolbox::GetRequest('dml');
$page	 = Toolbox::GetRequest('page');
$id		 = Toolbox::GetRequest('id');
$column	 = Toolbox::GetRequest('column');
$value	 = Toolbox::GetRequest('value');

//	Check DML
if(!$dml){
	$status	 = 'ERROR';
	$error	 = 'dml value is not set.';
	//	finish
	include('print.php');
}

//	Check page
if(!$page){
	$status	 = 'ERROR';
	$error	 = 'page value is not set.';
	//	finish
	include('print.php');
}

//	Get page config
$config = $this->GetConfigPage($page);

//	Check permit account
if(!$this->CheckPermitAccount( $config, $name, $role, $error )){
	$status	 = 'ERROR';
	//	finish
	include('print.php');
}

//	Check permit column
if(!$this->CheckPermitColumn( $config, $name, $role, $column, $dml, $error )){
	$status	 = 'ERROR';
	//	finish
	include('print.php');
}

//	execute each dml.
switch($dml){
	case 'insert':
		include('ajax.insert.php');
		break;
	case 'select':
		include('ajax.select.php');
		break;
	case 'update':
		include('ajax.update.php');
		break;
	case 'delete':
		include('ajax.delete.php');
		break;
	default:
		$status	 = 'ERROR';
		$error	 = 'undefined dml';
}

//	finish
include('print.php');
