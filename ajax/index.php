<?php
/* @var $this DevForce */
$st = microtime(true);

$this->SetEnv('layout',false);
$this->SetEnv('mime','text/javascript');

//	Get name, role
$id = $this->model('Login')->GetLoginID();
list( $name, $role ) = explode(':',$id);

$status	 = 0;
$error	 = '';
$page	 = Toolbox::GetRequest('page');
$id		 = Toolbox::GetRequest('id');
$column	 = Toolbox::GetRequest('column');
$value	 = Toolbox::GetRequest('value');

//	Check value
if(!$page){
	$status	 = 'ERROR';
	$error	 = 'page value is not set.';
	//	finish
	include('print.php');
}

//	Get page config
$config = $this->GetConfigPage($page);

//	Check permit
if(!$this->CheckPermit( $config, $name, $role )){
	$status	 = 'ERROR';
	$error	 = 'Not allowed.';
	//	finish
	include('print.php');
}

//	Init Update
if(!$update = $this->GetUpdateOfPage( $name, $role, $page, $error ) ){
	$status	 = 'ERROR';
	//	finish
	include('print.php');
}else{
	$io = $this->pdo()->Update($update);
	if( $io === false ){
		$error = "Update is failed.";
	}
}

//	finish
include('print.php');
