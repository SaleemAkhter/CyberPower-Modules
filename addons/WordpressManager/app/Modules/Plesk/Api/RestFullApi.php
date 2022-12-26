<?php

namespace ModulesGarden\WordpressManager\App\Modules\Plesk\Api;


use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\Operator\Cli;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\Operator\Clients;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\Operator\Domains;

class RestFullApi
{
    const API_URL = '/api/v2';
    const HTTP_GET = 'GET';
    const HTTP_PUT = 'PUT';
    const HTTP_POST ='POST';
    const HTTP_DELETE ='DELETE';
    private $host;
    private $port;
    private $protocol;
    private $login;
    private $password;
    protected $operators=[];
    protected $headers = [];
    protected $response;
    protected $parsedResponse;
    protected $requestUrl;
    protected $actionPath;
    protected $method;
    protected $request;
    private $curl;
    private $loger;
    protected  $curlInfo;


    /**
     * RestFullApi constructor.
     * @param $host
     * @param $port
     * @param $protocol
     */
    public function __construct($host, $port = 8443, $protocol = 'https')
    {
        $this->host     = $host;
        $this->port     = $port;
        $this->protocol = $protocol;
        $this->useJson();
    }

    public function useJson(){
        $this->headers[]= "Content-Type: application/json";
        $this->headers[]= "Accept: application/json";
    }

    public function setCredentials($login, $password){
        $this->login = $login;
        $this->password = $password;
        $this->headers[] = "Authorization: Basic " . base64_encode($this->login . ":" . $this->password);
        return $this;
    }

    /**
     * @return Cli
     */
    public function cli(){
        if(!isset($this->operators[__FUNCTION__])){
            $this->operators[__FUNCTION__] = new Cli($this);
        }
        return $this->operators[__FUNCTION__];
    }

    /**
     * @return Domains
     */
    public function domains(){
        if(!isset($this->operators[__FUNCTION__])){
            $this->operators[__FUNCTION__] = new Domains($this);
        }
        return $this->operators[__FUNCTION__];
    }

    /**
     * @return Clients
     */
    public function clients(){
        if(!isset($this->operators[__FUNCTION__])){
            $this->operators[__FUNCTION__] = new Clients($this);
        }
        return $this->operators[__FUNCTION__];
    }

    private function getRequestUrl(){
        return "{$this->protocol}://{$this->host}:{$this->port}".self::API_URL;
    }

    public function post($path, $request=[]){
        $this->method = self::HTTP_POST;
        $this->actionPath = $path;
        $this->request = $request;
        return $this->sendRequest();
    }

    public function get($path, $request=[]){
        $this->method = self::HTTP_GET;
        $this->actionPath = $path;
        $this->request = $request;
        return $this->sendRequest();
    }

    public function put($path, $request=[]){
        $this->method = self::HTTP_PUT;
        $this->actionPath = $path;
        $this->request = $request;
        return $this->sendRequest();
    }

    public function delete($path){
        $this->method = self::HTTP_DELETE;
        $this->actionPath = $path;
        $this->request = null;
        return $this->sendRequest();
    }

    private function sendRequest(){
        $this->requestUrl = $this->getRequestUrl().$this->actionPath;
        $this->curl              = curl_init();
        switch ($this->method)
        {
            case self::HTTP_GET:
                $this->requestUrl .="?". http_build_query($this->request);
                break;
            case self::HTTP_POST:
                if(is_array($this->request)){
                    $request = json_encode($this->request);
                }
                curl_setopt($this->curl, CURLOPT_POST, true);
                curl_setopt($this->curl, CURLOPT_POST, 1);
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $request);
                break;
            case self::HTTP_PUT:
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, self::HTTP_PUT);
                if(is_array($this->request)){
                    $request = json_encode($this->request);
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $request);
                }
                break;
            case self::HTTP_DELETE:
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, self::HTTP_DELETE);
                break;
        }
        curl_setopt($this->curl, CURLOPT_URL,  $this->requestUrl);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        return $this->parseResponse()->processResponse();

    }

    public function setLoger($loger)
    {
        $this->loger = $loger;
    }

    private function debug(){
        if (!$this->loger || !method_exists($this->loger, 'log'))
        {
            return;
        }
        $action = sprintf("GET: %s\r\nPOST: %s\r\n", $this->requestUrl, print_r($this->request, true));
        if($this->method == self::HTTP_GET){
            $action = sprintf("GET: %s\r\nPOST: %s\r\n", $this->requestUrl, null);
        }
        $this->loger->log($this->method, $action, $this->response, $this->parsedResponse);
    }

    private function parseResponse(){
        $this->response   = curl_exec($this->curl);
        $this->curlInfo = curl_getinfo($this->curl);
        $this->parsedResponse = json_decode($this->response , true);
        $this->debug();
        if (!$this->response)
        {
            $error = curl_error($this->curl);
            curl_close($this->curl);
            throw new \Exception('cURL Error: ' . $error);
        }
        curl_close($this->curl);
        return $this;
    }

    private function processResponse(){
        if($this->parsedResponse['stderr']){
            throw new \Exception($this->parsedResponse['stderr'], $this->parsedResponse['code']);
        }
        if($this->parsedResponse['stdout']  && preg_match('/\[\{/', $this->parsedResponse['stdout']) ||  preg_match('/\{\"/', $this->parsedResponse['stdout'])  ){
            $this->parsedResponse['stdout'] = json_decode($this->parsedResponse['stdout'], true);
        }
        if($this->curlInfo['http_code']== 404 && $this->parsedResponse['message']){
            throw new \Exception($this->parsedResponse['message'], $this->curlInfo['http_code']);
        }
        return $this->parsedResponse;
    }
}