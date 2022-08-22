<?php

class RequestTest extends \PHPUnit\Framework\TestCase{
    public function testPosiibleGetRequests(){
        $request = new \App\Request;
        //Get request without setting the options and headers
        $get_request=$request->setUrl('https://google.com')->get();
        $this->assertEquals($request->getCurlTemplate(),'curl https://google.com');

        //Get request with setting only the options
        $get_request=$request->setUrl('https://google.com')->setOptions(['--request'])->get();
        $this->assertEquals($request->getCurlTemplate(),'curl --request https://google.com');

        //Get request with setting only the Headers
        $get_request=$request->setUrl('https://google.com')->setHeaders(['Accept: application/json'])->get();
        $this->assertEquals($request->getCurlTemplate(),'curl --header "Accept: application/json" https://google.com');

        //Get request with setting options and headers
        $get_request=$request->setUrl('https://google.com')->setOptions(['--require'])->setHeaders(['Accept: application/json'])->get();
        $this->assertEquals($request->getCurlTemplate(),'curl --require --header "Accept: application/json" https://google.com');

        //Get request with setting only the headers
        $get_request=$request->setUrl('https://google.com')->setHeaders(['Accept: application/json'])->get();
        $this->assertEquals($request->getCurlTemplate(),'curl --header "Accept: application/json" https://google.com');

        //Get reuests with multiple options with one header
        $get_request=$request->setUrl('https://google.com')->setOptions(['--require','--location'])->setHeaders(['Accept: application/json'])->get();
        $this->assertEquals($request->getCurlTemplate(),'curl --require --location --header "Accept: application/json" https://google.com');

        //Get reuests with one option with multiple headers
        $get_request=$request->setUrl('https://google.com')->setOptions(['--require'])->setHeaders(['Accept: application/json','Authorization: Bearer {token}'])->get();
        $this->assertEquals($request->getCurlTemplate(),'curl --require --header "Accept: application/json" --header "Authorization: Bearer {token}" https://google.com');

        //Get request with Basic auth enabled
        $get_request=$request->setUrl('https://google.com')->setBasicAuth('username','password')->setOptions(['--require'])->setHeaders(['Accept: application/json'])->basicAuth()->get();
        $this->assertEquals($request->getCurlTemplate(),'curl --require --header "Accept: application/json" -u "username:password" https://google.com');
        
        //Get request with token auth enabled
        $get_request=$request->setUrl('https://google.com')->setToken('123456')->setOptions(['--require'])->setHeaders(['Accept: application/json'])->tokenAuth()->get();
        $this->assertEquals($request->getCurlTemplate(),'curl --require --header "Accept: application/json" --header "Authorization: Bearer {123456} https://google.com');
    }

    public function testPossiblePostRequests(){

        $request = new \App\Request;
        //Post request without setting the options and headers
        $post_request=$request->setUrl('https://google.com')->post(['name'=>'test','job'=>'test']);
        $this->assertEquals($request->getCurlTemplate(),'curl POST --data "{test&test}" https://google.com');

        //Post request with setting only the options
        $post_request=$request->setUrl('https://google.com')->setOptions(['--request'])->post(['name'=>'test','job'=>'test']);
        $this->assertEquals($request->getCurlTemplate(),'curl --request POST --data "{test&test}" https://google.com');

        //Post request with setting only the Headers
        $post_request=$request->setUrl('https://google.com')->setHeaders(['Accept: application/json'])->post(['name'=>'test','job'=>'test']);
        $this->assertEquals($request->getCurlTemplate(),'curl POST --header "Accept: application/json" --data "{test&test}" https://google.com');

        //Post request with setting options and headers
        $post_request=$request->setUrl('https://google.com')->setOptions(['--require'])->setHeaders(['Accept: application/json'])->post(['name'=>'test','job'=>'test']);
        $this->assertEquals($request->getCurlTemplate(),'curl --require POST --header "Accept: application/json" --data "{test&test}" https://google.com');

        //Post request with setting only the headers
        $post_request=$request->setUrl('https://google.com')->setHeaders(['Accept: application/json'])->post(['name'=>'test','job'=>'test']);
        $this->assertEquals($request->getCurlTemplate(),'curl POST --header "Accept: application/json" --data "{test&test}" https://google.com');

        //Post reuests with multiple options with one header
        $post_request=$request->setUrl('https://google.com')->setOptions(['--require','--location'])->setHeaders(['Accept: application/json'])->post(['name'=>'test','job'=>'test']);
        $this->assertEquals($request->getCurlTemplate(),'curl --require --location POST --header "Accept: application/json" --data "{test&test}" https://google.com');

        //post requests with one option with multiple headers
        $post_request=$request->setUrl('https://google.com')->setOptions(['--require'])->setHeaders(['Accept: application/json','Authorization: Bearer {token}'])->post(['name'=>'test','job'=>'test']);
        $this->assertEquals($request->getCurlTemplate(),'curl --require POST --header "Accept: application/json" --header "Authorization: Bearer {token}" --data "{test&test}" https://google.com');

        //Post request with Basic auth enabled
        $post_request=$request->setUrl('https://google.com')->setBasicAuth('username','password')->setOptions(['--require'])->setHeaders(['Accept: application/json'])->post(['name'=>'test','job'=>'test']);
        $this->assertEquals($request->getCurlTemplate(),'curl --require POST --header "Accept: application/json" --data "{test&test}" https://google.com');

        //Postrequest with token auth enabled
        $post_request=$request->setUrl('https://google.com')->setToken('123456')->setOptions(['--require'])->setHeaders(['Accept: application/json'])->tokenAuth()->post(['name'=>'test','job'=>'test']);
        $this->assertEquals($request->getCurlTemplate(),'curl --require POST --header "Accept: application/json" --header "Authorization: Bearer {123456} --data "{test&test}" https://google.com');
    }

    public function testPossiblePutRequests(){
        $request = new \App\Request;
        //Put request without setting the options and headers
        $put_request=$request->setUrl('https://google.com')->put(['name'=>'test','job'=>'test'],1);
        $this->assertEquals($request->getCurlTemplate(),'curl PUT --data "{test&test}" https://google.com?id=1');

        //Put request with setting only the options
        $put_request=$request->setUrl('https://google.com')->setOptions(['--request'])->put(['name'=>'test','job'=>'test'],1);
        $this->assertEquals($request->getCurlTemplate(),'curl --request PUT --data "{test&test}" https://google.com?id=1');

        //Put request with setting only the Headers
        $put_request=$request->setUrl('https://google.com')->setHeaders(['Accept: application/json'])->put(['name'=>'test','job'=>'test'],1);
        $this->assertEquals($request->getCurlTemplate(),'curl PUT --header "Accept: application/json" --data "{test&test}" https://google.com?id=1');

        //Put request with setting options and headers
        $put_request=$request->setUrl('https://google.com')->setOptions(['--require'])->setHeaders(['Accept: application/json'])->put(['name'=>'test','job'=>'test'],1);
        $this->assertEquals($request->getCurlTemplate(),'curl --require PUT --header "Accept: application/json" --data "{test&test}" https://google.com?id=1');

        //Put request with setting only the headers
        $put_request=$request->setUrl('https://google.com')->setHeaders(['Accept: application/json'])->put(['name'=>'test','job'=>'test'],1);
        $this->assertEquals($request->getCurlTemplate(),'curl PUT --header "Accept: application/json" --data "{test&test}" https://google.com?id=1');

        //Put reuests with multiple options with one header
        $put_request=$request->setUrl('https://google.com')->setOptions(['--require','--location'])->setHeaders(['Accept: application/json'])->put(['name'=>'test','job'=>'test'],1);
        $this->assertEquals($request->getCurlTemplate(),'curl --require --location PUT --header "Accept: application/json" --data "{test&test}" https://google.com?id=1');

        //Put reuests with one option with multiple headers
        $put_request=$request->setUrl('https://google.com')->setOptions(['--require'])->setHeaders(['Accept: application/json','Authorization: Bearer {token}'])->put(['name'=>'test','job'=>'test'],1);
        $this->assertEquals($request->getCurlTemplate(),'curl --require PUT --header "Accept: application/json" --header "Authorization: Bearer {token}" --data "{test&test}" https://google.com?id=1');

        //put request with Basic auth enabled
        $put_request=$request->setUrl('https://google.com')->setBasicAuth('username','password')->setOptions(['--require'])->setHeaders(['Accept: application/json'])->basicAuth()->put(['name'=>'test','job'=>'test'],1);
        $this->assertEquals($request->getCurlTemplate(),'curl --require PUT --header "Accept: application/json" -u "username:password" --data "{test&test}" https://google.com?id=1');
        
        //put request with token auth enabled
        $put_request=$request->setUrl('https://google.com')->setToken('123456')->setOptions(['--require'])->setHeaders(['Accept: application/json'])->tokenAuth()->put(['name'=>'test','job'=>'test'],1);
        $this->assertEquals($request->getCurlTemplate(),'curl --require PUT --header "Accept: application/json" --header "Authorization: Bearer {123456} --data "{test&test}" https://google.com?id=1');
    }

    public function testPossibleDeleteRequests(){
        $request = new \App\Request;
        //Delete request without setting the options and headers
        $delete_request=$request->setUrl('https://google.com')->delete(1);
        $this->assertEquals($request->getCurlTemplate(),'curl DELETE https://google.com?id=1');

        //Delete request with setting only the options
        $delete_request=$request->setUrl('https://google.com')->setOptions(['--request'])->delete(1);
        $this->assertEquals($request->getCurlTemplate(),'curl --request DELETE https://google.com?id=1');

        //Delete request with setting only the Headers
        $delete_request=$request->setUrl('https://google.com')->setHeaders(['Accept: application/json'])->delete(1);
        $this->assertEquals($request->getCurlTemplate(),'curl DELETE --header "Accept: application/json" https://google.com?id=1');

        //Delete request with setting options and headers
        $delete_request=$request->setUrl('https://google.com')->setOptions(['--require'])->setHeaders(['Accept: application/json'])->delete(1);
        $this->assertEquals($request->getCurlTemplate(),'curl --require DELETE --header "Accept: application/json" https://google.com?id=1');

        //Delete request with setting only the headers
        $delete_request=$request->setUrl('https://google.com')->setHeaders(['Accept: application/json'])->delete(1);
        $this->assertEquals($request->getCurlTemplate(),'curl DELETE --header "Accept: application/json" https://google.com?id=1');

        //Delete reuests with multiple options with one header
        $delete_request=$request->setUrl('https://google.com')->setOptions(['--require','--location'])->setHeaders(['Accept: application/json'])->delete(1);
        $this->assertEquals($request->getCurlTemplate(),'curl --require --location DELETE --header "Accept: application/json" https://google.com?id=1');

        //Delete reuests with one option with multiple headers
        $delete_request=$request->setUrl('https://google.com')->setOptions(['--require'])->setHeaders(['Accept: application/json','Authorization: Bearer {token}'])->delete(1);
        $this->assertEquals($request->getCurlTemplate(),'curl --require DELETE --header "Accept: application/json" --header "Authorization: Bearer {token}" https://google.com?id=1');

        //Delete request with Basic auth enabled
        $delete_request=$request->setUrl('https://google.com')->setBasicAuth('username','password')->setOptions(['--require'])->setHeaders(['Accept: application/json'])->basicAuth()->delete(1);
        $this->assertEquals($request->getCurlTemplate(),'curl --require DELETE --header "Accept: application/json" -u "username:password" https://google.com?id=1');
        
        //Delete request with token auth enabled
        $delete_request=$request->setUrl('https://google.com')->setToken('123456')->setOptions(['--require'])->setHeaders(['Accept: application/json'])->tokenAuth()->delete(1);
        $this->assertEquals($request->getCurlTemplate(),'curl --require DELETE --header "Accept: application/json" --header "Authorization: Bearer {123456} https://google.com?id=1');
    }
}