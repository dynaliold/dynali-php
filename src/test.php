<?php
include "DynaliException.php";
include "DynaliClient.php";

$c = new Dynali\DynaliClient();
var_dump($c::status('test.dynali.net', 'openwrt', 'test'));
