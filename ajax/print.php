<?php
/* @var $this DevForce */

//	set header
//header('Content-Type: text/html; charset=UTF-8', true, 201);

//	create json
$json = array();
$json['file']	 = __FILE__;
$json['status']	 = $status;
$json['error']	 = $error;
$json['page']	 = $page;
$json['id']		 = $id;
$json['column']	 = $column;
$json['value']	 = $value;
$json['time']	 = microtime(true) - $st;

print json_encode($json);
$this->Content();
die();
