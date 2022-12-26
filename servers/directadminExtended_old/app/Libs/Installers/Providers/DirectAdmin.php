<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Providers;

use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces\InstallerProvider;
use \ModulesGarden\Servers\DirectAdminExtended\Core\HandlerError\Exceptions\Exception;

/**
 * Description of PleskProvider
 * 
 */
class DirectAdmin implements InstallerProvider
{
    /**
     *
     * @var string Response Format [serialize] [xml] [json]
     */
    private $api          = 'json';
    private $response     = null;
    private $jsonResponse = null;
    private $requestUrl   = null;
    private $sessionNeed  = true;
    private $get          = [];
    private $post         = [];
    private $params       = [];
    private $loger;
    private $hostUrl;
    private $sso;
    private $curl;

    private $cookie         = null;

    const COOKIE_JAR            = 'cookies.txt';

    function __construct($installer, $params = [])
    {
        $this->params = $params;
        $this->setHostUrl(ucfirst($installer));
    }

    //Default installer has to get this params
    public function getParams()
    {
        return $this->params;
    }

    public function login()
    {
        return $this;
    }

    function getLoger()
    {
        return $this->loger;
    }

    public function setLoger($loger)
    {
        $this->loger = $loger;
    }

    function getGet()
    {
        return $this->get;
    }

    function getPost()
    {
        return $this->post;
    }

    public function setGet($get)
    {
        $this->get = $get;
    }

    public function setPost($post)
    {
        $this->post = $post;
    }

    public function get($get)
    {
        $this->setGet($get);

        return $this->sendRequest();
    }

    public function post($get, $post = [], $filter = true)
    {
        $this->setGet($get);
        ($filter) ? $this->setPost(array_filter($post)) : $this->setPost($post);

        return $this->sendRequest();
    }

    private function initCurl()
    {
        $this->curl = curl_init();

        return $this;
    }

    private function setCurlOpts()
    {
        curl_setopt($this->curl, CURLOPT_VERBOSE, 1);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($this->curl, CURLOPT_COOKIE, $this->cookie);
        curl_setopt($this->curl, CURLOPT_REFERER, $this->getHost());

        //curl_setopt($this->curl, CURLOPT_USERPWD, $this->params['username'].':'.$this->params['password']);

        return $this;
    }

    private function setCurlUrl($url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);

        return $this;
    }

    private function setCurlPostFields()
    {
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->post);

        return $this;
    }

    private function execute()
    {
        return curl_exec($this->curl);
    }

    private function initSession()
    {
        $this->initCurl()
                ->setCurlOpts()
                ->authenticate()
                ->setCurlOpts()
                ->setCurlUrl($this->hostUrl)
                ->execute();

        return $this;
    }

    private function setRequestUrl()
    {

        $this->requestUrl = $this->hostUrl;;
        $this->requestUrl .= '?' . http_build_query($this->get);


        return $this;
    }

    public function sendRequest()
    {
        $this->response     = null;
        $this->jsonResponse = null;

        $this->initSession()
                ->setRequestUrl()
                ->setCurlUrl($this->requestUrl);

        if (!empty($this->post))
        {
            $this->setCurlPostFields();
        }

        $this->response     = $this->execute();

        $this->jsonResponse = json_decode($this->response, true);
        $this->debug();


        if ($this->response === false)
        {
            $error = curl_error($this->curl);
            curl_close($this->curl);
            throw new Exception(self::class, 'cURL Error: ' . $error);
        }
        curl_close($this->curl);
        $this->processResponse();
        return $this->jsonResponse;
    }

    private function debug()
    {
        if (!$this->loger || !method_exists($this->loger, 'log'))
        {
            return;
        }
        $action = print_r(sprintf("GET: %s\r\nPOST: %s\r\n", $this->requestUrl, print_r($this->post, true)), true);
        $this->loger->log($this->get['act'], $action, $this->response, $this->jsonResponse);
    }

    private function processResponse()
    {
        if (isset($this->jsonResponse['error']))
        {
            throw new \Exception(implode(",", $this->jsonResponse['error']));
        }
        if (isset($this->jsonResponse['result']) && $this->jsonResponse['result'] === false)
        {
            throw new \Exception($this->jsonResponse['message']);
        }
        if (!$this->jsonResponse['result'] && strpos($this->jsonResponse['message'], 'error') !== false)
        {
            throw new \Exception($this->jsonResponse['message']);
        }
    }

    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Execute method by pattern {InstallerName}SetUrl
     * 
     * @param type $installer
     * @throws \Exception if method does not exist
     */
    private function setHostUrl($installer)
    {
        $method = $installer . 'SetUrl';
        if (!method_exists($this, $method))
        {
            throw new Exception(self::class, 'DirectAdmin provider: ' . $method . ' method does not exist');
        }
        $this->{$method}();
    }

    private function SoftaculousSetUrl()
    {
        $this->prepareEndpoint('softaculous');
    }

    private function InstallatronSetUrl()
    {
        $this->prepareEndpoint('installatron');
    }

    private function getHost()
    {
        $protocol = ($this->params['serversecure'] === 'on' || $this->params['serversecure'] === true) ? 'https' : 'http';
        $host     = $this->params['serverhostname'] ? $this->params['serverhostname'] : $this->params['serverip'];
        $port     = $this->params['serverport'] ? $this->params['serverport'] : 2222;

        return $protocol . '://'.$host . ':' . $port;
    }

    private function prepareEndpoint($application){

        $this->hostUrl =  $this->getHost(). '/CMD_PLUGINS/'.$application.'/index.raw';
    }

    /**
     * @return $this
     * @TODO refactor me
     */
    private function authenticate()
    {
        if($this->cookie)
        {
            return $this;
        }

        $this->setCurlUrl($this->getHost().'/CMD_LOGIN');
        $post   = [
            'username'  => $this->params['serverusername'] . '|' . $this->params['username']  ,
            'password'  => $this->params['serverpassword'],
            'referer'   => '/'
        ];

        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($this->curl, CURLOPT_HEADER, true);

        $data   = $this->execute();

        $res = explode("\n", $data);

        // Find the cookies
        foreach($res as $k => $v)
        {
            if(preg_match('/^'.preg_quote('set-cookie:', '/').'(.*?)$/is', $v, $mat) && empty($this->cookie))
            {
                $this->cookie = trim($mat[1]);
            }
        }

        return $this;
    }
}
