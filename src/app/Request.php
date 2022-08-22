<?php

namespace App;

class Request{

    private $url;//Base url
    private $options=array();//options of the curl requests
    private $headers=array();//headers of the curl requests
    private $curl_template;//curl requests template
    private $data=array();

    private $is_url_available=false;
    private $is_set_options=false;//is user set the options?.
    private $is_set_headers=false;//is user set the headers?

    private $is_requesting_json=false;
    private $is_requesting_xml=false;

    private $username;//username for basic auth.
    private $password;//password for basic auth.
    private $token;//token

    private $is_basic_auth=false;//is basic auth is enabled.
    private $is_token_auth=false;//is token auth is enabled.


    /**
     * @method getCurlTemplate
     * @return curl template
     */
    public function getCurlTemplate(){
        return $this->curl_template;
    }

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
    public function tokenAuth(){
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
        $this->is_url_available=true;
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
        try{
            $passHeader;
            if(!empty($this->headers)){
                $passHeader="";
                foreach($this->headers as $value){
                    $passHeader="$passHeader -H  \"$value\"";
                }
            }
        return $passHeader;
        }catch(Exception $error){
            echo $error->getMessage();
        }
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
        try{
            $passOptions;
            if(!empty($this->options)){
                $passOptions="";
                foreach($this->options as $value){
                    $passOptions="$passOptions $value";
                }
            }
        return $passOptions;
        return $this;
        }catch(Exception $error){
            echo $error->getMessage();
        }
    }

    /**
     * @method createCommand 
     * build the curl command based on the options,headers,methods based on the user request. 
     * @param method = http request method,
     * @param postData = array of post/put data Array
     * @return String curl command.
     */
    public function createCommand($method,$postData=NULL){
        if($this->is_url_available){
            try{
                $format='';
                if($this->is_requesting_json){
                    $format="-H \"Accept: application/json\"";
                    $this->is_requesting_xml=false;
                }elseif($this->is_requesting_xml){
                    $format="-H \"Accept: application/xml\"";
                    $this->is_requesting_json=false;
                }

                $options='';
                if($this->is_set_options){
                   $options=$this->options;
                }

                $headers='';
                if($this->is_set_headers){
                    $headers=$this->headers;
                }
                
                $url=$this->url;
                $method= strtoupper($method);
                $curl_template;
        
                $username='';
                $password='';
                $pass_credentials='';
                if($this->is_basic_auth){
                    $username=$this->username;
                    $password=$this->password;
                    $pass_credentials="-u \"$username:$password\"";
                }
        
                $getToken='';
                $token='';
                if($this->is_token_auth){
                    $getToken=$this->token;
                    $token="-H \"Authorization: Bearer {{$getToken}}";
                }
                
                $curl_template="curl $options $method $headers $format $pass_credentials $token $postData $url";
                $curl_template=preg_replace('/\s+/', ' ',$curl_template);
                
                $this->curl_template=$curl_template;
                return $curl_template;
                
            }catch(Exception $error){
                echo $error->getMessage();
            }
        }else{
            throw new Exception("Please provide an url");
        }
    }
    /**
     * @method get get Request
     */
    public function get(){
        try{
            $command=$this->createCommand('get');
            return self::send($command);
        }catch(Exception $error){
            echo $error->getMessage();
        }
    }

    /**
     * @method post post Request
     * @param data = array of post data
     */
    public function post($data=array()){
        try{
            $postData=$this->buildData($data);
            $command=$this->createCommand('post',$postData);
            return self::send($command);
        }catch(Exception $error){
            echo $error->getMessage();
        }
    }

    /**
     * @method put put Request
     * @param data = array of put data
     * @param id = id of the table data to be updated.
     */
    public function put($data=array(),$id){
        try{
            $putData=$this->buildData($data);
            $query_url=$this->buildUrl($this->url,["id"=>"$id"]);
            $this->url=$query_url;
            $temp_url=$this->url;
            $command=$this->createCommand('put',$putData);
            $this->url=$temp_url;
            return self::send($command);
        }catch(Exception $error){
            echo $error->getMessage();
        }
    }

    /**
     * @method delete delete Request
     * @param id = id of the table data to be deleted
     */
    public function delete($id){
        try{
            $query_url=$this->buildUrl($this->url,["id"=>"$id"]);
            $temp_url=$this->url;
            $this->url=$query_url;
            $command=$this->createCommand('delete');
            $this->url=$temp_url;
            return self::send($command);
        }catch(Exception $error){
            echo $error->getMessage();
        }
    }

    /**
     * @method build data 
     * This method will build a string based on an arrary's data
     * @param dataArray = array of data
     * @return passData = string made from array data.
     */
    public function buildData($dataArray=array()){
        try{
            $passData;
            $length=count($dataArray);
            if(!empty($dataArray)){
                $passData="";
                foreach($dataArray as $key=>$value){
                    if(++$dataArray!=$length){
                        $passData="$passData$value&";
                    }else{
                        $passData="$passData$value";
                    }
                }
            }
            $passData=str_replace(' ', '', $passData);
            $passData=substr_replace($passData,'',-1);
            $passData="-d \"{{$passData}}\"";
            return $passData;
        }catch(Exception $error){
            echo $error->getMessage();
        }
    }

    /**
     * @method build data 
     * This method will build an url based on an query patameters
     * @param url = base url
     * @param dataArray = array of queries
     * @return retUrl= url made from query parameters
     */
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
	}

    public function json(){
        $this->is_requesting_json=true;
    }

    public function xml(){
        $this->is_requesting_xml=true;
    }

    /**
     * @method send
     * This method will execute the curl command
     * @param command - curl command
     */
    private function send($command){
        try{
            $results=shell_exec($command);
            var_dump($this->curl_template);
            $this->is_set_options=false;
            $this->is_set_headers=false;
            echo "$results";
        }catch(Exception $e){
            echo 'Message: ' .$e->getMessage();
        }
    }
}
