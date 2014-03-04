<?php
/* @var $this DevForce */

//	get delete config
if(!$delete = $this->GetDeleteConfig( $page, $error ) ){
	$status	 = 'ERROR';
	return;
}

//	execute delete
$io = $this->pdo()->Delete($delete);
if( $io === false ){
	$error = "Delete is failed.";
}
