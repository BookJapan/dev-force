<?php

$_form = new Config();

$_form->name = 'login_form';

$input_name = 'account';
$_form->input->$input_name->type	 = 'text';

$input_name = 'password';
$_form->input->$input_name->type	 = 'password';
$_form->input->$input_name->validate->required = true;

$input_name = 'submit_button';
$_form->input->$input_name->label	 = '';
$_form->input->$input_name->type	 = 'submit';
$_form->input->$input_name->value	 = ' Login ';
