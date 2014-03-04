<?php
/* @var $this DevForce */

//	set header
$mime = 'text/plain';
//$mime = 'text/javascript';
//$mime = 'application/json';
header("Content-Type: $mime; charset=UTF-8", true, 201);

//	query
$query = $this->PDO()->Qu();

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
$json['query']	 = $query;

print json_encode($json);
$this->Content();
die();
