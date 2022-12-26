<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 11, 2017)
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

namespace ModulesGarden\WordpressManager\App\Modules\Cpanel;

use \ModulesGarden\WordpressManager\App\Helper\LangException;
use ModulesGarden\WordpressManager\App\Helper\Loger;

/**
 * Description of Uapi
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class Uapi
{
    protected $ch      = null;
    protected $token   = null;
    protected $expires;
    protected $username;
    protected $password;
    protected $host;
    protected $port;
    protected $headers = [];
    protected $ssl;
    protected $sessionUrl;
    protected $queryuser;
    private $loger     = null;
    private $lastResponse;
    private $lastJsonResponse;
    private $wp;

    public function setLogin($params)
    {
        $this->host      = $params['serverhostname'] ? $params['serverhostname'] : $params['serverip'];
        $this->username  = $params['serverusername'];
        $this->password  = $params['serveraccesshash'] ? trim($params['serveraccesshash']) : $params['serverpassword'];
        $this->headers[] = $params['serveraccesshash'] ? 'Authorization: WHM ' . $this->username . ':' . str_replace(array("\r", "\n"), "", $this->password) : "Authorization: Basic " . base64_encode($this->username . ":" . $this->password);
        $this->ssl       = $params['serversecure'] ? true : false;
        $this->port      = $params['serverport'] ? $params['serverport'] : '2087';
        $this->queryuser = $params['username'];
        return $this;
    }

    function getLoger()
    {
        return $this->loger;
    }

    function getSessionUrl()
    {
        return $this->sessionUrl;
    }

    function getUapi()
    {
        return $this->ch;
    }

    function setLoger($loger)
    {
        $this->loger = $loger;
    }

    public function createSession($service = 'cpaneld')
    {
        $query    = ($this->ssl ? 'https' : 'http') . "://" . $this->host . ":" . $this->port
            . "/json-api/create_user_session?api.version=1&user=" . $this->queryuser . "&service=" . $service;
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($this->ch, CURLOPT_URL, $query);
        $response = curl_exec($this->ch);
        if (!$response) {
            $error = curl_error($this->ch);
            curl_close($this->ch);
            throw (new LangException("Curl error: " . $error))->translate();
        }

        $jsonResponse = json_decode($response, true);
        if ($jsonResponse['cpanelresult']['error']) {
            throw (new LangException("UAPI error: " . $jsonResponse['cpanelresult']['error']))->translate();
        } else if ($jsonResponse['metadata']['result'] === 0) {
            throw (new LangException("UAPI error: " . $jsonResponse['metadata']['reason']))->translate();
        }
        $this->initSession($jsonResponse);
    }

    private function initSession($jsonResponse)
    {
        $tab        = explode(':', $jsonResponse['data']['url']);
        $sessionUrl = $tab[0] . '://' . $this->host . ':' . $tab[2];
        $cookieJar  = 'cookie.txt';
        curl_setopt($this->ch, CURLOPT_URL, $sessionUrl);
        curl_setopt($this->ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $cookieJar);
        $response   = curl_exec($this->ch);
        if (!$response) {
            $error = curl_error($this->ch);
            curl_close($this->ch);
            throw (new LangException("Curl error: " . $error))->translate();
        }

        $this->sessionUrl = preg_replace('{/login(?:/)??.*}', '', $sessionUrl);
    }

    public function exec($name, $api, $request = array())
    {
        $this->requestUrl = $this->sessionUrl . $api . $name;
        if ($request) {
            $this->requestUrl .= '?' . http_build_query($request);
        }
        
        curl_setopt($this->ch, CURLOPT_URL, $this->requestUrl);
        $this->lastResponse = curl_exec($this->ch);
        $this->debug();
        if (!$this->lastResponse) {
            $error = curl_error($this->ch);
            curl_close($this->ch);
            throw (new LangException("Curl error: " . $error))->translate();
        }
        return $this->processResponse();
    }

    private function processResponse()
    {
        $replace = [
            '\\\n'  => "",
            '\\\u2014' => " - ",
            '"[{'  => '[{',
            '}]"'  => '}]',
            '\"'   => '"',
            '\\\\' => '',

        ];
        $response = $this->lastResponse;
        if (preg_match('/PHP\sWarning\:/', $response)) {
            $response = preg_replace('/PHP\sWarning\:(.*)\[\{/', '[{', $response);
        }
        $response               = str_replace(array_keys($replace), array_values($replace), $response);
        $this->lastJsonResponse = json_decode($response, true);
        if (!$this->lastJsonResponse && preg_match("/\{\"/", $response)) {
            $ex = explode('{"', $response);
            $response = str_replace($ex[0], "", $response);
            $this->lastJsonResponse = json_decode($response, true);
        }

        if(!$this->lastJsonResponse) {
           $ex = explode('\n', $response);
           $remove = strpos($ex[0], '"data":"');
           $str = substr($ex[0], $remove);
           $str = str_replace('"data":"', '', $str);
           $str = json_decode($str, true);
           $this->lastJsonResponse = $str;
        }

        if ($this->lastJsonResponse['errors'] && preg_match("/Failed to load module/", implode(" ", $this->lastJsonResponse['errors'])) && preg_match("/Wordpress/", implode(" ", $this->lastJsonResponse['errors']))) {
            throw new \Exception("Please install WHM extension based on documentation: <a  target=\"_blank\" href=\"https://www.docs.modulesgarden.com/WordPress_Manager_For_WHMCS#cPanel_.28WHM.29_Installation\">  cPanel (WHM) Installation</a>");
        }
        if ($this->lastJsonResponse['errors']) {
            throw (new LangException("UAPI error: " . trim(implode(", ", $this->lastJsonResponse['errors']))))->translate();
        } 
        if ($this->lastJsonResponse['data'] && preg_match('/Warning/', $this->lastJsonResponse['data']) && !preg_match('/Success/', $this->lastJsonResponse['data'])) {
            throw (new LangException("UAPI error: " . trim($this->lastJsonResponse['data'])))->translate();
        }

        return $this->lastJsonResponse;
    }

    private function debug()
    {
       /*  $action = print_r(sprintf("GET: %s\r\nPOST: %s\r\n", $this->requestUrl, print_r($this->post, true)), true);
        logModuleCall('WP', $action, str_replace($this->sessionUrl, "", $this->requestUrl), $this->lastJsonResponse ?? $this->lastJsonResponse); */

        if (!$this->loger || !method_exists($this->loger, 'log')) {
            return;
        }

        $action = print_r(sprintf("GET: %s\r\nPOST: %s\r\n", $this->requestUrl, print_r($this->post, true)), true);
        $this->loger->log(str_replace($this->sessionUrl, "", $this->requestUrl), $action, $this->lastResponse, $this->jsonResponse);
    }

    /**
     *
     * @param string $path
     * @return Wp
     */
    public function wp($path)
    {
        if (!is_null($this->wp) && $this->wp->getPath() == $path) {
            return $this->wp;
        }
        $this->wp = new Wp($path);
        $this->wp->setUapi($this);
        return $this->wp;
    }

    function getLastResponse()
    {
        return $this->lastResponse;
    }

    function setLastResponse($lastResponse)
    {
        $this->lastResponse = $lastResponse;
    }

    /**
     * @return mixed
     */
    public function getQueryuser()
    {
        return $this->queryuser;
    }

    /**
     * @param mixed $queryuser
     * @return Uapi
     */
    public function setQueryuser($queryuser)
    {
        $this->queryuser = $queryuser;
        return $this;
    }
}
