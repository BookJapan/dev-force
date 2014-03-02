<?php
/* @var $this DevForce */

$form_config = $this->form()->AddForm('./form.config.php');
//$this->d($form_config);

if( $this->form()->Secure($form_config->name) ){
	$config = $this->GetConfig('account');
//	$this->d($config);

	$name = $this->form()->GetInputValue('account', $form_config->name);
	$pass = $this->form()->GetInputValue('password',$form_config->name);
	if( empty($config->$name) ){
		$this->mark('This account is does not exists.');
	}else if( $config->$name->pass !== $pass ){
		$this->mark('Does not match password.');
	}else{
		$this->mark("Match password.");
		$this->model('Login')->SetLoginID("$name:".$config->$name->role);
		include('success.phtml');
		return;
	}
}else{
//	$this->form()->Debug($form_config->name);
}

include('form.phtml');
