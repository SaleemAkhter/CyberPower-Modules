<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Jan 17, 2018)
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

namespace ModulesGarden\WordpressManager\App\Modules\Softaculous;

use ModulesGarden\WordpressManager\App\Interfaces\SoftaculousApiProvider;
use \ModulesGarden\WordpressManager\App\Helper\LangException;
/**
 * Description of DirectAdminProvider
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class DirectAdminProvider implements SoftaculousApiProvider
{
    /**
     *
     * @var string Response Format [serialize] [xml] [json]
     */
    private $api          = 'json';
    private $response     = null;
    private $jsonResponse = null;
    private $requestUrl   = null;
    private $get          = [];
    private $post         = [];
    private $loger;
    private $cookie       = null;
    private $curlHeader;
    private $protocol;
    private $host;
    private $port;
    private $adminUsername;
    private $adminPassword;
    private $username;

    function __construct($protocol, $host, $port, $adminUsername, $adminPassword, $username)
    {
        $this->protocol      = $protocol;
        $this->host          = $host;
        $this->port          = $port;
        $this->adminUsername = $adminUsername;
        $this->adminPassword = $adminPassword;
        $this->username      = $username;
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

    function getAdminUsername()
    {
        return $this->adminUsername;
    }

    function getAdminPassword()
    {
        return $this->adminPassword;
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

    function setAdminUsername($adminUsername)
    {
        $this->adminUsername = $adminUsername;
    }

    function setAdminPassword($adminPassword)
    {
        $this->adminPassword = $adminPassword;
    }

    function setUsername($username)
    {
        $this->username = $username;
    }

    public function login()
    {


        $this->curlHeader = true;
        $hostUrl          = "{$this->protocol}{$this->host}:{$this->port}";
        $loginUrl         = "{$hostUrl}/CMD_LOGIN";
        $login = "{$this->adminUsername}|{$this->username}";
        if($this->adminUsername == $this->username){
            $login = $this->adminUsername;
        }
        // if(isset($_SESSION['da_logincookie']) && $_SESSION['da_logincookie_valid_till']>time()){
        //     $this->cookie=$_SESSION['da_logincookie'];
        //     return $this;
        // }

        $this->post       = [
            'username' => $login,
            'password' => $this->adminPassword,
            'referer'  => '/',
        ];
        $ch               = curl_init();
        curl_setopt($ch, CURLOPT_URL, $loginUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->post));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $hostUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
        $this->response   = curl_exec($ch);
        $this->debug();
        if ($this->response === false)
        {
            $error = curl_error($ch);
            curl_close($ch);
            throw (new LangException('cURL Error: ' . $error))->translate();
        }
        curl_close($ch);
        $res = explode("\n", $this->response);
        // Find the cookies
        foreach ($res as $k => $v)
        {
            if (preg_match('/^' . preg_quote('set-cookie:', '/') . '(.*?)$/is', $v, $mat))
            {
                $this->cookie = trim($mat[1]);
                break;
            }
        }
        if (!$this->cookie)
        {
            throw (new LangException(sprintf("API: Login to Direct Admin host '%s' failed", $loginUrl)))->translate();
        }
        $_SESSION['da_logincookie']=$this->cookie;
        $_SESSION['da_logincookie_valid_till']=time()+30;
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

    public function sendRequest()
    {

        $this->response     = null;
        $this->jsonResponse = null;
        $login              = "{$this->adminUsername}|{$this->username}:{$this->adminPassword}";
        if($this->adminUsername == $this->username){
            $login = "{$this->adminUsername}:{$this->adminPassword}";
        }
        $authLogin = array(
            'Content-Type:application/json',
            'Authorization: Basic '. base64_encode($login)
        );
        $hostUrl            = "{$this->protocol}{$this->host}:{$this->port}";
        $this->requestUrl   = "{$this->protocol}{$this->host}:{$this->port}/CMD_PLUGINS/softaculous/index.raw";
        $this->requestUrl   .= '?' . http_build_query($this->get);
        $ch                 = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $authLogin);
        curl_setopt($ch, CURLOPT_URL, $this->requestUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 400);

        if (!empty($this->post))
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->post));
        }
        if ($this->cookie)
        {
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
            curl_setopt($ch, CURLOPT_COOKIE, $this->cookie);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $hostUrl);
        $this->response     = curl_exec($ch);
        // logModuleCall("directadminapi",$this->get['act'], [$this->get,$this->post,$this->requestUrl,$login], $this->response);
        if(isset($this->get['act']) && $this->get['act']=="import" && !isset($this->post['softdomain'])){
            // logModuleCall("directadminapi","importcheck", [$this->get,$this->post,$this->requestUrl,$login], $this->response);
            return $this->response ;
        }
        if(isset($this->get['act']) && $this->get['act']=="sync"){
            // logModuleCall("directadminapi","importcheck", [$this->get,$this->post,$this->requestUrl,$login], $this->response);
            return json_decode($this->response, true); ;
        }
        $this->jsonResponse = json_decode($this->response, true);
        $this->debug();
        if (!$this->response)
        {
            $error = curl_error($ch);
            curl_close($ch);
            // logModuleCall("directadminapi",$this->get['act'], [$this->get,$this->post,$this->requestUrl,$login], $error);
            throw (new LangException('cURL Error: ' . $error))->translate();
        }
        curl_close($ch);
        $this->processResponse();

        return $this->jsonResponse;
    }

    private function debug()
    {
        if (!$this->loger || !method_exists($this->loger, 'log'))
        {
            return;
        }
        $action = sprintf("GET: %s\r\nPOST: %s\r\n", $this->requestUrl, print_r($this->post, true));
        // $this->loger->log("lplplpl".$this->get['act'], __FILE__.$action, $this->response, $this->jsonResponse);
    }

    private function processResponse()
    {
        if (isset($this->jsonResponse['error']))
        {
            throw (new LangException(implode(",", $this->jsonResponse['error'])))->translate();
        }
    }

    public function getResponse()
    {
        return $this->response;
    }
}
