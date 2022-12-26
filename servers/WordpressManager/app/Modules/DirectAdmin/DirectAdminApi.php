<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Jan 18, 2018)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\Modules\DirectAdmin;

use \ModulesGarden\WordpressManager\App\Helper\LangException;
use ModulesGarden\WordpressManager\App\Modules\DirectAdmin\Models\AddonDomain;

/**
 * Description of Api
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class DirectAdminApi
{
    private $protocol;
    private $username;
    private $password;
    private $port;
    private $curlInfo;
    private $response;
    private $parsedResponse;
    private $adminUsername;
    

    /**
     *
     * @var Wp
     */
    private $wp;

    function __construct($protocol, $host, $port, $username, $password)
    {
        $this->protocol = $protocol;
        $this->host     = $host;
        $this->port     = $port;
        $this->username = $username;
        $this->password = $password;
    }
    
    public function getAdminUsername()
    {
        return $this->adminUsername;
    }

    public function setAdminUsername($adminUsername)
    {
        $this->adminUsername = $adminUsername;
        return $this;
    }

    function getProtocol()
    {
        return $this->protocol;
    }

    function getHost()
    {
        return $this->host;
    }

    function getPort()
    {
        return $this->port;
    }

    function getUsername()
    {
        return $this->username;
    }

    function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    function setHost($host)
    {
        $this->host = $host;
    }

    function setPort($port)
    {
        $this->port = $port;
    }

    function setUsername($username)
    {
        $this->username = $username;
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

    public function sendRequest($returnraw=false)
    {
        $this->response     = null;
        $this->parsedResponse = null;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->requestUrl);;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->authHeaders);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        if (!empty($this->post))
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->post));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $this->response = curl_exec($ch);
        $this->curlInfo = curl_getinfo($ch);
        if(!strpos($this->requestUrl, 'CMD_API_SHOW_USERS')){
            logModuleCall("directadminapi","sendRequest11-".$this->curlInfo['http_code'], [$this->get,$this->post,$this->requestUrl], $this->response);
        }

        $this->parseResponse();
        $this->curlInfo = curl_getinfo($ch);
        $this->debug();
        if (!$this->response && (!isset($this->curlInfo['http_code']) || $this->curlInfo['http_code']!=200))
        {
            $error = curl_error($ch);
            curl_close($ch);
            
            throw (new LangException('cURL Error: ' . $error))->translate();
        }
        curl_close($ch);
        $this->processResponse();
        if($returnraw){
            curl_close($ch);
            return $this->response;
        }
        return $this->parsedResponse;
    }

    private function parseResponse()
    {
        $this->parsedResponse = [];
        $in                   = null;
        foreach (explode('&', $this->response) as $item)
        {
            list($key, $value) = explode('=', $item);
            $in .= str_replace('%2E', '%3A%3Cdot%3E%3A', $key) . "=$value&";
        }
        trim($in, '&');
        parse_str($in, $arr);
        foreach ($arr as $k => $v)
        {
            $k                        = str_replace(':<dot>:', '.', $k);
            $this->parsedResponse[$k] = $v;
        }
    }

    private function debug()
    {
        if (!$this->loger || !method_exists($this->loger, 'log'))
        {
            return;
        }
        $action = print_r(sprintf("GET: %s\r\nPOST: %s\r\n", $this->requestUrl, print_r($this->post, true)), true);
        if(!$action){
            $action=$this->requestUrl;
        }
        $this->loger->log($this->get['act'], __FILE__.$action,  debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT,4), $this->response);
    }

    private function processResponse()
    {
        if (isset($this->parsedResponse['error']) && is_array($this->parsedResponse['error']))
        {
            throw (new LangException(implode(",", $this->parsedResponse['error'])))->translate();
        }else if(isset($this->parsedResponse['error']) && $this->parsedResponse['error']!=0 && isset($this->parsedResponse['details'])){
            throw (new LangException($this->parsedResponse['details']))->translate();
        }
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getAddonDomains($user)
    {
        $this->setGet(['user' => $user]);
        $this->setPost([]);
        $login            = "{$this->username}:{$this->password}";
        $hostUrl          = "{$this->protocol}{$this->host}:{$this->port}";

        $this->setAuthHeaders($login);

        $this->requestUrl = "{$this->protocol}{$this->host}:{$this->port}/CMD_API_SHOW_USER_DOMAINS";
        $this->requestUrl .= '?' . http_build_query($this->get);
        return $this->sendRequest();
    }

    public function addAddonDomain(array $request)
    {
        $this->setGet([]);
        $request['action'] = 'create';
        $this->setPost($request);
        $login             = "{$this->username}:{$this->password}";
        $hostUrl           = "{$this->protocol}{$this->host}:{$this->port}";

        $this->setAuthHeaders($login);

        $this->requestUrl  = "{$this->protocol}{$this->host}:{$this->port}/CMD_API_DOMAIN";
        $this->requestUrl  .= '?' . http_build_query($this->get);
        return $this->sendRequest();
    }

    /**
     * @param $domain
     * @return AddonDomain
     */
    public function getAddonDomain($domain)
    {
        $this->setPost([]);
        $this->setGet([
            'action'    => 'view',
            'domain'    => $domain,
        ]);
        $this->setCommand('CMD_API_ADDITIONAL_DOMAINS');

        return AddonDomain::fromSource($this->sendRequest());
    }

    /**
     * @param $domain
     * @return AddonDomain
     */
    public function getSubDomais($domain)
    {
        $this->setPost([]);
        $this->setGet([
            'domain'    => $domain,
        ]);
        $this->setCommand('CMD_API_SUBDOMAINS');
        return $this->sendRequest();
    }

    public function addSubDomain( $subdomain, $domain)
    {
        $this->setGet([]);
        $request=[
            'subdomain' => $subdomain,
            'domain' =>  $domain,
            'action' => 'create'
        ];
        $this->setPost($request);
        $login             = "{$this->username}:{$this->password}";

        $this->setAuthHeaders($login);

        $this->requestUrl  = "{$this->protocol}{$this->host}:{$this->port}/CMD_API_SUBDOMAINS";
        $this->requestUrl  .= '?' . http_build_query($this->get);
        return $this->sendRequest();
    }

    public function changeDomain($oldDomain, $newDomain)
    {
        $this->setGet([]);
        $request               = [];
        $request['old_domain'] = $oldDomain;
        $request['new_domain'] = $newDomain;
        $request['action']     = 'change';
        $this->setPost($request);
        $hostUrl               = "{$this->protocol}{$this->host}:{$this->port}";
        $login            = "{$this->username}:{$this->password}";

        $this->setAuthHeaders($login);

        $this->requestUrl      = "{$this->protocol}{$this->host}:{$this->port}/CMD_API_CHANGE_DOMAIN";
        $this->requestUrl      .= '?' . http_build_query($this->get);
        return $this->sendRequest();
    }

    public function sendWpRequest()
    {
        $hostUrl          = "{$this->protocol}{$this->host}:{$this->port}";
        $login            = "{$this->username}:{$this->password}";

        $this->setAuthHeaders($login);

        $this->requestUrl = "{$this->protocol}{$this->host}:{$this->port}/CMD_PLUGINS/wordpress_cli/script.raw";
        if ($this->get)
        {
            $this->requestUrl .= '?' . http_build_query($this->get);
        }
        $this->sendRequest();
        $this->parsedResponse = json_decode($this->response, true);

        if($this->parsedResponse) {
            return $this->parsedResponse;
        } else {
            return $this->response;
        }
    }

    /**
     * 
     * @param string $path
     * @return Wp
     */
    public function wp($path)
    {
        if (!is_null($this->wp) && $this->wp->getPath() == $path)
        {
            return $this->wp;
        }
        $this->wp = new Wp($path);
        $this->wp->setApi($this);
        return $this->wp;
    }
    
    public function setCommand ($command){
        $login             = "{$this->username}:{$this->password}";
        $hostUrl           = "{$this->protocol}{$this->host}:{$this->port}";

        $this->setAuthHeaders($login);

        $this->requestUrl = "{$this->protocol}{$this->host}:{$this->port}/{$command}";
        $this->requestUrl  .= '?' . http_build_query($this->get);
        return $this;
    }

    /**
     * @param AddonDomain $domain
     * @return null
     */
    public function updateAddonDomain(AddonDomain $domain)
    {
        $data               = $domain->toArray();
        $data['action']     = 'modify';

        $this->setGet([]);
        $this->setPost($data);
        $this->setCommand('CMD_API_DOMAIN');

        return $this->sendRequest();
    }

    /**
     * @param AddonDomain $domain
     * @return null
     */
    public function getFileList($path)
    {

        $this->setGet([
            // 'json'=>'yes',
            'filemanager_du'=>0,
            'action'=>'json_all',
            'path'=>$path,
            'ipp'=>1000,
            'page'=>1
        ]);
        $this->setPost([]);
        $this->setCommand('CMD_FILE_MANAGER');

        return $this->sendRequest(true);
    }

    /**
     * @param $domain
     * @param $ssl
     * @return null
     */
    public function setDomainSsl($domain, $ssl)
    {
        $domain = $this->getAddonDomain($domain);
        $domain->setSsl($ssl);

        return $this->updateAddonDomain($domain);
    }

    /**
     * @param $domain
     * @return null
     */
    public function toggleDomainSslOn($domain)
    {
        return $this->setDomainSsl($domain, true);
    }

    /**
     * @param $domain
     * @return null
     */
    public function toggleDomainSslOff($domain)
    {
        return $this->setDomainSsl($domain, false);
    }

    public function setAuthHeaders($authLogin)
    {
        $this->authHeaders = [
            'Content-Type: application/json',
            'Authorization: Basic '. base64_encode($authLogin)
        ];
    }
}
