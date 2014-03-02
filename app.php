<?php
/**
 * Skeleton of OnePiece Application.
 * 
 * This is general OnePiece application settings.
 * 
 * @version   1.0
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright 2009 (C) Tomoaki Nagahara All right reserved.
 */

/**
 *  Set OnePiece directory.
 * 
 *  Example:
 *  $op_dir = '/www/op-core';
 *  $op_dir = 'C:\www\op-core';
 */ 
$op_dir = '/var/www/common/op-core/master';

//  Start time
$st = microtime(true);

//  Check
if( $op_dir ){
	
	//  php include path
	$include_path = rtrim(ini_get('include_path'), PATH_SEPARATOR) .PATH_SEPARATOR. $op_dir;
	ini_set('include_path',$include_path);
	
	//  include OnePiece
	$path = $op_dir.DIRECTORY_SEPARATOR.'OnePiece5.class.php';
	if( file_exists($path) ){
		include($path);
		unset($include_path);
	}else{
		print "<p>$op_dir is not place OnePiece5.class.php</p>";
		print "<p>please check ".__FILE__."</p>";
		exit(0);
	}
}else{
	if(!@include('OnePiece5.class.php')){
		$file = __FILE__;
		print "OnePiece is not found. <br/>\nPlease edit \$op_dir in $file.";
		exit(0);
	}
}

//  Error reporting
error_reporting(E_ALL);

//  Create App object.
include('./DevForce.app.php');
$app = new DevForce();

//	Set Admin IP-address
//$app->SetEnv('admin-ip','');

//	Application setup
$app->Setup();

//  Enable html pass through.
$app->SetHtmlPassThrough(true);

//  Set mime.
$app->SetMime('text/html');

//  Set charset.
$app->SetCharset('utf-8');

//  Set controller file name.
$app->SetControllerName('index.php');

//  Set setting file name.
$app->SetSettingName('setting.php');

//  Set Layout directory
$app->SetLayoutDir('app:/zlib/layout');

//  Set Layout in layout directory
$app->SetLayoutName('flat');

//  Set template directory
$app->SetTemplateDir('app:/zlib/template');

//  Set model directory
$app->SetModelDir('app:/zlib/model');

//  Set default title
$app->SetTitle("Developer force for BookJapan");

//  Set default keywords
$app->SetKeywords('');

//  Set default description
$app->SetDescription("");

//  Google analytics tracking ID
//$app->SetEnv('google-analytics-tracking-id','UA-123456789-1');

//  Execute target file.
$app->Dispatch();

//  memory check
$app->mark('memory check');

//  Finish time
$en = microtime(true);

//  lap time
printf('<div>Execute time is %s seconds.</div>', $en - $st );
