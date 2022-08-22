<?php

require_once 'Request.php';

$request = new App\Request();

$request->setUrl('https://reqres.in/api/users');
//$request->setOptions(['--require','--location'])->setHeaders(['Content-Type:application/json'])->post(['email'=>'abcd@gmail.com','password'=>'123456']);
//$request->put(['name'=>'test','job'=>'tester'],10);
//$request->setOptions(['--require','--location','-X'])->custom('--form',['username'])->put(['abc'=>'sdas'],1);
// $request->delete(10);

$request->get();