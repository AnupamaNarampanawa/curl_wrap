<?php

require_once 'Request.php';

$request = new App\Request();

$request->setUrl('https://google.com');

$request->get();

//$request->setOptions(['-X'])->setHeaders(['Content-Type: application/json'])->post(['name'=>'test','job'=>'tester']);

//$request->put(['name'=>'test','job'=>'tester'],1);
