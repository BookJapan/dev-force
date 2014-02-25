<?php
/* @var $this App */

//  1st (Smart)URL value.
$args1 = isset($args[1]) ? $args[1]: null;
switch( $args1 ){
	case null:
		$mes1 = 'Does not select 1st value.';
		break;
		
	case 'Apple':
		$mes1 = 'You are select Apple.';
		break;
		
	default:
		$mes1 = 'You are select undefined value.';
}

//  2nd (Smart)URL value.
$args2 = isset($args[2]) ? $args[2]: null;
switch( $args2 ){
	case null:
	case '':
		$mes2 = 'Does not select 2nd value.';
		break;

	case 'iPod':
		$mes2 = 'You are select iPod.';
		break;

	case 'iPhone':
		$mes2 = 'You are select iPhone.';
		break;	

	case 'Mac':
		$mes2 = 'You are select Mac.';
		break;
			
	default:
		$mes2 = 'You are select undefined value.';
}

$data = new Config();
$data->mes1 = $mes1;
$data->mes2 = $mes2;
