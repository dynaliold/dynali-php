<?php
/*
include "../lib/DynaliException.php";
include "../lib/DynaliClient.php";
include "../lib/DynaliStatus.php";
*/
$requiredFields = ['ip', 'status', 'status_message', 'expiry_date', 'created', 'last_update'];
$test = ['ip', 'status', 'status_message', 'expiry_date', 'created'];

var_dump(array_diff($requiredFields, $test));
/*
$c = new Dynali\DynaliClient();
var_dump($c::myIp());
//var_dump($c::status('test.dynali.net', 'openwrt', 'test')); */
