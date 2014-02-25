<?php
/* @var $this DevForce */

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
