<?php

require_once 'Request.php';

$request = new Request();
$request->setUrl('https://reqres.in/api/users');
//$request->setHeaders(["Accept: application/json"])->setOptions(["--location"])->get();
// $request->setToken("123456");
// $request->token();
// $request->post(['name'=>'tset','job'=>'tester']);

$request->setOptions(["--request","--location"])->setHeaders(['Accept: application/json'])->post(['name'=>'test','job'=>'tester']);
