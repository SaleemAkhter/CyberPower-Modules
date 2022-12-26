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
 * Description of CpanelProvider
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class CpanelProvider implements SoftaculousApiProvider
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
    private $hostUrl;
    private $username;
    private $password;
    private $headers          = [];
    private $accessKey;

    function __construct($hostUrl)
    {
        $this->hostUrl = $hostUrl;
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

    public function sendRequest()
    {
        $this->response     = null;
        $this->jsonResponse = null;
        $this->requestUrl   = $this->hostUrl;
        $this->requestUrl   .= '?' . http_build_query($this->get);

        if($this->headers['uapi'])
        {
            $this->execUapi($this->headers['uapi']->getUapi());
            return $this->jsonResponse;
        }

        $ch                 = curl_init();
        if ($this->headers)
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        }
        curl_setopt($ch, CURLOPT_URL, $this->requestUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        if (!empty($this->post))
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->post));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $this->response     = curl_exec($ch);
        $this->info = curl_getinfo($ch);
        $this->jsonResponse = json_decode($this->response, true);
        $this->debug();
        if (!$this->response)
        {
            $error = curl_error($ch);
            curl_close($ch);
            throw (new LangException('cURL Error: ' . $error))->translate();
        }
        curl_close($ch);
        $this->processResponse();
        return $this->jsonResponse;
    }

    private function execUapi($uapi)
    {
        curl_setopt($uapi, CURLOPT_URL, $this->requestUrl);
        if (!empty($this->post))
        {
            curl_setopt($uapi, CURLOPT_POST, 1);
            curl_setopt($uapi, CURLOPT_POSTFIELDS, http_build_query($this->post));
        }
        $this->response = curl_exec($uapi);
        $this->info = curl_getinfo($uapi);
        $this->jsonResponse = json_decode($this->response, true);
        $this->debug();
        if (!$this->response)
        {
            $error = curl_error($uapi);
            curl_close($uapi);
            throw (new LangException("Curl error: " . $error))->translate();
        }
        $this->processResponse();
    }

    private function debug()
    {
        if (!$this->loger || !method_exists($this->loger, 'log'))
        {
            return;
        }
        $action = sprintf("GET: %s\r\nPOST: %s\r\n", $this->requestUrl, print_r($this->post, true));
        $this->loger->log($this->get['act'], $action, $this->response, $this->jsonResponse);
    }

    private function processResponse()
    {
        if (isset($this->jsonResponse['error']))
        {
            throw (new LangException(implode(",", $this->jsonResponse['error'])))->translate();
        }

        if ($this->info['http_code']==401)
        {
            throw (new LangException("Login to cPanel host failed"))->translate();
        }

    }

    public function getResponse()
    {
        return $this->response;
    }

    public function authorizationBasic()
    {
        $this->headers['0'] = "Authorization: Basic " . base64_encode($this->username . ":" . $this->password) . "\n\r";
        return $this;
    }

    public function authorizationWhm()
    {
        $this->headers['0'] = 'Authorization: WHM ' . $this->username . ':' . str_replace(array("\r", "\n"), "", $this->accessKey);
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getAccessKey()
    {
        return $this->accessKey;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setAccessKey($accessKey)
    {
        $this->accessKey = $accessKey;
        return $this;
    }

    function setHeader($headers)
    {
        $this->headers = $headers;
    }
}
