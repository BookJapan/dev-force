<?php
/* @var $this DevForce */
$this->mark();

$this->model('Login')->Logout();

include('./logout.phtml');
