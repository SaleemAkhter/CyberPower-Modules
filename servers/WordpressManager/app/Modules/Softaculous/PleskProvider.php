<?php


namespace ModulesGarden\WordpressManager\App\Modules\Softaculous;


use ModulesGarden\WordpressManager\App\Helper\LangException;
use ModulesGarden\WordpressManager\App\Interfaces\SoftaculousApiProvider;

class PleskProvider implements SoftaculousApiProvider
{
    private $curl;
    private $host;
    private $sessionId;
    private $port;
    private $protocol;
    const PLESKSESSID ='PLESKSESSID';
    const PHPSESSID = 'PHPSESSID';
    const SESSION_URL = 'enterprise/rsession_init.php';
    const SOFTACULOUS_URL = 'modules/softaculous/index.php';
    const WPM_CLI_URL = 'modules/wordpress-manager-cli/index.php';

    private $response;
    private $jsonResponse;
    private $get          = [];
    private $post         = [];
    private $curlInfo = [];
    private $requestUrl;
    private $cookies =[];
    private $requestUri;

    /**
     * PleskProvider constructor.
     * @param $host
     * @param $sessionId
     * @param $port
     * @param $protocol
     */
    public function __construct($host, $sessionId, $port = 8443, $protocol = 'https')
    {
        $this->host      = $host;
        $this->sessionId = $sessionId;
        $this->port      = $port;
        $this->protocol  = $protocol;
        $this->setRequestUri(self::SOFTACULOUS_URL);
    }

    private function getSessionUrl(){
        $sessionUrl = "{$this->protocol}://{$this->host}:{$this->port}/".self::SESSION_URL;
        $sessionUrl .='?'. http_build_query([
            self::PLESKSESSID => $this->sessionId,
            self::PHPSESSID => $this->sessionId,
        ]);
        return $sessionUrl;
    }

    public function setRequestUrl($requestUrl){
        $this->requestUrl = $requestUrl;
        return $this;
    }

    private function getRequestUrl(){
        return "{$this->protocol}://{$this->host}:{$this->port}/".$this->getRequestUri();
    }

    private function getRequestUri(){
        return $this->requestUri;
    }

    /**
     * @param mixed $requestUri
     */
    public function setRequestUri($requestUri)
    {
        $this->requestUri = $requestUri;
    }


    public function login()
    {
        $this->sessionRequest();
        $this->sessionInit();
        return $this;
    }

    private function sessionRequest(){
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL,     $this->getSessionUrl());
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($this->curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        $this->response = curl_exec($this->curl);
    }

    private function sessionInit(){

        curl_setopt($this->curl, CURLOPT_URL, $this->getSessionUrl());
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, null);
        curl_setopt($this->curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, 'cookies.txt');
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, 'cookies.txt');
        $this->response = curl_exec($this->curl);
    }

    public function setLoger($loger)
    {
        $this->loger = $loger;
    }

    public function setGet($get)
    {
        $this->get = $get;
    }

    public function setPost($post)
    {
        $this->post = $post;
    }

    public function sendRequest()
    {
        $this->response     = null;
        $this->jsonResponse = null;
        $this->requestUrl   = $this->getRequestUrl();
        $this->requestUrl   .= '?' . http_build_query($this->get);
        curl_setopt($this->curl, CURLOPT_URL, $this->requestUrl);
        if($this->hasCookies()){
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, ["Cookie: ".$this->getCookies()]);
        }
        if (!empty($this->post))
        {
            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($this->post));
        }
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 30);

        $this->response     = curl_exec($this->curl);
        $this->curlInfo = curl_getinfo($this->curl);
        $this->jsonResponse = json_decode($this->response, true);
        $this->debug();
        if (!$this->response)
        {
            $error = curl_error($this->curl);
            curl_close($this->curl);
            throw (new LangException('cURL Error: ' . $error))->translate();
        }
        $this->processResponse();
        return $this->jsonResponse;
    }

    public function getResponse()
    {
        return $this->response;
    }

    private function processResponse(){
        if (isset($this->jsonResponse['error']))
        {
            throw (new LangException(implode(",", $this->jsonResponse['error'])))->translate();
        }else if(is_null($this->jsonResponse) && preg_match("/login\.php/", $this->response)){
            throw new \Exception(sprintf("Login to Plesk host '%s' failed", $this->getRequestUrl()),401);
        }
    }

    private function debug(){
        if (!$this->loger || !method_exists($this->loger, 'log'))
        {
            return;
        }
        $request = sprintf("Cookie: %s\r\nGET: %s\r\nPOST: %s\r\n",$this->getCookies(), $this->requestUrl, print_r($this->post, true));
        $action = $this->get['act'] ? $this->get['act'] : $this->post['0']. " ".$this->post['1'];
        $this->loger->log( $action,$request, $this->response, $this->jsonResponse);
    }

    public function __destruct()
    {
        if($this->curl){
            curl_close($this->curl);
        }
    }

    public function setCookieSoftdomId($domainId){
        $this->cookies['softdomid'] = $domainId;
        if($this->sessionId){
            $this->cookies['PLESKSESSID'] = $this->sessionId;
        }
        return $this;
    }
    
    private function getCookies(){
        $cookies = [];
        foreach ($this->cookies as $cookie  => $value) {
            $cookies[] = "{$cookie}={$value}";
        }
        return implode("; ", $cookies);
    }

    private function hasCookies(){
        return !empty($this->cookies);
    }

    public function call($post){
        $this->get =[];
        $this->post = $post;
        return $this->sendRequest();
    }

}
