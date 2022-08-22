<?php

class Request{

    private $url;//Base url
    private $options=array();//options of the curl requests
    private $headers=array();//headers of the curl requests
    private $curl_template;//curl requests template

    private $is_set_options=false;//is user set the options?.
    private $is_set_headers=false;//is user set the headers?

    private $username;//username for basic auth.
    private $password;//password for basic auth.
    private $token;//token

    private $is_basic_auth=false;//is basic auth is enabled.
    private $is_token_auth=false;//is token auth is enabled.

    /**
     * @method setBasicAuth
     * set the credentials username and the password for basic auth.
     * @param username = username,
     * @param password = password,
     */
    public function setBasicAuth($username,$password){
        $this->username=$username;
        $this->password=$password;
        return $this;
    }

     /**
     * @method setToken
     * set the token for token auth.
     * @param token = token,
     */
    public function setToken($token){
        $this->token=$token;
        return $this;
    }

    /**
     * @method token
     * set the authenticate type to auth.
     */
    public function token(){
        $this->is_token_auth=true;
        $this->is_basic_auth=false;
        return $this;
    }

    /**
     * @method basicAuth
     * set the authenticate type to basicAuth auth.
     */
    public function basicAuth(){
        $this->is_basic_auth=true;
        $this->is_token_auth=false;
        return $this;
    }

    /**
     * @method setUrl
     * set the base url.
     * @param url = base url.
     */
    public function setUrl($url){
        $this->url=$url;
        return $this;
    }

     /**
     * @method setHeaders
     * set the headers of the curl request.
     * @param headers = array of headers to be set.
     */
    public function setHeaders($headers=array()){
        if(!empty($headers)){
            $this->is_set_headers=true;
            $this->headers=$headers;
            $this->headers=$this->buildHeader();
        }
        return $this;
    }

     /**
     * @method buildHeader
     * build a String of headers to be concatinate withe the curl request.
     * @param headers = array of headers to be set.
     * @return String Headers.
     */
    public function buildHeader(){
        $passHeader;
            if(!empty($this->headers)){
                $passHeader="";
                foreach($this->headers as $value){
                    $passHeader="$passHeader -H  \"$value\"";
                }
            }
        return $passHeader;
        return $this;
    }

     /**
     * @method setOptions
     * set the options to be send woth the curl request.
     * @param optionsArray = array ofoptions to be set.
     */
    public function setOptions($optionsArray=array()){
        if(!empty($optionsArray)){
            $this->is_set_options=true;
            $this->options=$optionsArray;
            $this->options=$this->buildOptions();
        }
        return $this;
    }

     /**
     * @method buildOptions
     * build a String of options to be concatinate withe the curl request.
     * @return String options.
     */
    public function buildOptions(){
        $passOptions;
            if(!empty($this->options)){
                $passOptions="";
                foreach($this->options as $value){
                    $passOptions="$passOptions $value";
                }
            }
        return $passOptions;
        return $this;
    }

    /**
     * @method createCommandbuild a String of options to be concatinate withe the curl request.
     * build the curl command based on the options,headers,methods based on the user request. 
     * @param method = http request method,
     * @param postData = array of post/put data
     * @return String curl command.
     */
    public function createCommand($method,$postData=NULL){

        $options=$this->options;
        $headers=$this->headers;
        $url=$this->url;
        $method= strtoupper($method);
        $curl_template;

        $username=$this->username;
        $password=$this->password;
        $pass_credentials="-u \"$username:$password\"";

        $getToken=$this->token;
        $token="-H \"Authorization: Bearer {{$getToken}}";
    
    
        if(!$this->$is_set_options && !$this->is_set_headers){
            if($method=='POST' || $method=='PUT'){
                if($this->is_basic_auth){
                    $curl_template="curl $method $pass_credentials -d \"{{$postData}}\" $url a";
                }elseif($this->is_token_auth){
                    $curl_template="curl $method $token -d \"{{$postData}}\" $url a";
                }else{
                    $curl_template="curl $method -d \"{{$postData}}\" $url";
                }
            }else{
                if($this->is_basic_auth){
                    $curl_template="curl $method $pass_credentials $url";
                }elseif($this->is_token_auth){
                    $curl_template="curl $method $token $url";
                }else{
                    $curl_template="curl $method $url";
                }
                
            }        
        }

        if(!$this->is_set_option && $this->is_set_headers){
            if($method=='POST' || $method=='PUT'){
                if($this->is_basic_auth){
                    $curl_template="curl $method $headers $pass_credentials \"{{$postData}}\" $url";
                }elseif($this->is_token_auth){
                    $curl_template="curl $method $headers $token \"{{$postData}}\" $url";
                }else{
                    $curl_template="curl $method $headers \"{{$postData}}\" $url";
                }
            }else{
                if($this->is_basic_auth){
                    $curl_template="curl $method $headers $pass_credentials $url";
                }elseif($this->is_token_auth){
                    $curl_template="curl $method $headers $token $url";
                }else{
                    $curl_template="curl $method $headers $url";
                }
                
            }
        }

        if($this->is_set_options && !$this->is_set_headers){
            if($method=='POST' || $method=='PUT'){
                if($this->is_basic_auth){
                    $curl_template="curl $options $method $pass_credentials \"{{$postData}}\" $url";
                }elseif($this->is_token_auth){
                    $curl_template="curl $options $method $token \"{{$postData}}\" $url";
                }else{
                    $curl_template="curl $options $method \"{{$postData}}\" $url";
                }
            }else{
                if($this->is_basic_auth){
                    $curl_template="curl $options $method $pass_credentials $url";
                }elseif($this->is_token_auth){
                    $curl_template="curl $options $method $token $url";
                }else{
                    $curl_template="curl $options $method $url";
                }
            }
        }

        if($this->is_set_headers && $this->is_set_options){
            if($method=='POST' || $method=='PUT'){
                if($this->is_basic_auth){
                    $curl_template="curl $options $method $headers $pass_credentials \"{{$postData}}\" $url";
                }elseif($this->is_token_auth){
                    $curl_template="curl $options $method $headers $token \"{{$postData}}\" $url";
                }else{
                    $curl_template="curl $options $method $headers \"{{$postData}}\" $url";
                }
            }else{
                if($this->is_basic_auth){
                    $curl_template="curl $options $method $headers $pass_credentials $url";
                }elseif($this->is_token_auth){
                    $curl_template="curl $options $method $headers $token $url";
                }else{
                    $curl_template="curl $options $method $headers $url";
                }
                
            }
           
        }
        return $curl_template;
    }

    
    public function get(){
        $command=$this->createCommand('get');
        return self::send($command);
        return $this;
    }

    public function post($data=array()){
        $postData=$this->buildData($data);
        $command=$this->createCommand('post',$postData);
        return self::send($command);
        return $this;
    }

    public function put($id,$data=array()){
        $putData=$this->buildData($data);
        $query_url=$this->buildUrl($this->url,["id"=>"$id"]);
        $this->url=$query_url;
        $temp_url=$this->url;
        $command=$this->createCommand('put',$putData);
        $this->url=$temp_url;
        return self::send($command);
        return $this;
    }

    public function delete($id){
        $query_url=$this->buildUrl($this->url,["id"=>"$id"]);
        $temp_url=$this->url;
        $this->url=$query_url;
        $command=$this->createCommand('delete');
        $this->url=$temp_url;
        return self::send($command);
        return $this;
    }

    public function buildData($dataArray=array()){
        $passData;
        $length=count($dataArray);
        if(!empty($dataArray)){
            foreach($dataArray as $value){
                if(++$d!=$length){
                    $passData="$passData $value&";
                }else{
                    $passData="$passData $value";
                }
            }
        }
        return $passData;
        return $this;
    }

    public static function buildUrl($url, array $query)
	{
		if (empty($query)) {
			return $url;
		}

		$parts = parse_url($url);
		$queryString = '';
		if (isset($parts['query']) && $parts['query']) {
			$queryString .= $parts['query'].'&'.http_build_query($query);
		} else {
			$queryString .= http_build_query($query);
		}
		$retUrl = $parts['scheme'].'://'.$parts['host'];
		if (isset($parts['port'])) {
			$retUrl .= ':'.$parts['port'];
		}
		if (isset($parts['path'])) {
			$retUrl .= $parts['path'];
		}
		if ($queryString) {
			$retUrl .= '?' . $queryString;
		}

		return $retUrl;
        return $this;
	}

    private function send($command){
            var_dump($command);
            $results=shell_exec($command);
            var_dump($results);
    }
}