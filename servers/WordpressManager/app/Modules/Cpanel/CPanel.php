<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Jan 11, 2018)
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
/**
 * Description of CPanel
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class CPanel
{
    private static $allowedAPIs = array(
        'WHM0', 'WHM1', 'API1', 'API2', 'UAPI'
    );
    private static $instance    = false;
    private $host;
    private $port;
    private $username;
    private $password;
    private $ssl;
    private $authtype;
    private $queryuser;
    private $url;
    private $api                = false;
    private $module             = false;
    private $function           = false;
    private $output             = 'json';
    private $tempout;
    private $uapi;

    public function setLogin($params)
    {
        $this->host     = $params['serverhostname'] ? $params['serverhostname'] : $params['serverip'];
        $this->username = $params['serverusername'];

        if ($params['serveraccesshash'])
        {
            $this->authtype = 0;
            $this->password = trim($params['serveraccesshash']);
        }
        else
        {
            $this->authtype = 1;
            $this->password = $params['serverpassword'];
        }


        $this->ssl  = $params['serversecure'] ? true : false;
        if ($this->ssl)
            $this->port = '2087';
        else
            $this->port = '2086';

        if ($params['username'])
            $this->queryuser = $params['username'];

        $this->curlCustomConfig = array();
        if (file_exists(dirname(__FILE__) . DS . 'curl_options.ini'))
            $this->curlCustomConfig = parse_ini_file(dirname(__FILE__) . DS . 'curl_options.ini');
    }

    public function __get($name)
    {
        if (in_array(strtoupper($name), self::$allowedAPIs))
        {
            $this->api = strtoupper($name);
        }
        else
        if ($this->api !== false)
        {
            $this->module = $name;
        }
        else
        {
            throw (new LangException('API ' . $name . 'is not supported'))->translate();
        }
        return $this;
    }

    public function __call($name, $arguments)
    {
        if ($this->api === false)
        {
            throw (new LangException('API is not specified'))->translate();
        }

        if (($this->api == 'API1' || $this->api == 'API2') && $this->module == false)
        {
            throw (new LangException('Module is not specified'))->translate();
        }

        $this->function = $name;
        if (is_null($arguments[1]) || empty($arguments[1]))
        {
            $this->tempout = $this->output;
        }
        else
        {
            $this->tempout = $arguments[1];
        }

        $result = '';
        switch ($this->api)
        {
            case 'UAPI': $result = $this->sendUAPIRequest($arguments[0]);
                return $result;
                break;
            case 'API1': $result = $this->sendAPI1Request($arguments[0]);
                break;
            case 'API2': $result = $this->sendAPI2Request($arguments[0]);
                break;
            case 'WHM0': $result = $this->sendWHMRequest($arguments[0], 0);
                break;
            case 'WHM1': $result = $this->sendWHMRequest($arguments[0], 1);
                break;
        }

        if (is_null($arguments[2]) || !isset($arguments[2]))
        {
            $this->checkIfThereIsNoErrorInResult($result);
        }
        else
        {
            if ($arguments[2] === true)
            {
                $this->checkIfThereIsNoErrorInResult($result);
            }
        }

        if ($this->tempout == 'array')
        {
            return json_decode($result, TRUE);
        }

        if ($this->tempout == 'obj')
        {

            return json_decode($result);
        }

        if ($this->tempout == 'sarray' || $this->tempout == 'sobj')
        {
            $array = json_decode($result, TRUE);

            array_walk_recursive($array, function(&$v)
            {
                $v = htmlspecialchars($v, ENT_QUOTES);
            });

            if ($this->tempout == 'sarray')
            {
                return $array;
            }
            else
            {
                return json_decode(json_encode($array));
            }
        }



        unset($this->tempout);
        return $result;
    }

    //api1
    private function sendAPI1Request($arguments)
    {
        list($api, $cpuser, $module_type, $func_type, $api_type) = $this->getQueryArray();
        $this->url = ($this->ssl === true ? 'https://' : 'http://') . rawurlencode($this->host) . ':' . rawurlencode($this->port)
                . '/' . $api . '/cpanel?' . $cpuser . '=' . $this->queryuser . '&' . $module_type . '=' . rawurlencode($this->module)
                . '&' . $func_type . '=' . rawurlencode($this->function) . '&' . $api_type . '=1';
        $argcount  = 0;
        foreach ($arguments as $a)
        {
            $this->url .= '&arg-' . $argcount . '=' . rawurlencode(trim($a));
            $argcount++;
        }

        return $this->execRequest();
    }

    private function sendUAPIRequest($arguments)
    {
        $end = '/execute/' . rawurlencode($this->module) . '/' . rawurlencode($this->function) . '?' . http_build_query($arguments);
        //$uapi = MG_CP_CPanelUAPI::getInstance(MG_CP_Container::$data->params);

        $ex = $this->uapi->execQuery($end);

        return $ex;
    }

    private function sendAPI2Request($arguments)
    {
        list($api, $cpuser, $module_type, $func_type, $api_type) = $this->getQueryArray();

        $this->url = ($this->ssl === true ? 'https://' : 'http://') . rawurlencode($this->host) . ':' . rawurlencode($this->port)
                . '/' . $api . '/cpanel?' . $cpuser . '=' . $this->queryuser . '&' . $module_type . '=' . rawurlencode($this->module)
                . '&' . $func_type . '=' . rawurlencode($this->function) . '&' . $api_type . '=2';

        while (list($action, $val) = each($arguments))
        {
            $this->url .= '&' . rawurlencode($action) . '=' . rawurlencode(trim($val));
        }

        return $this->execRequest();
    }

    private function sendWHMRequest($arguments, $apiVersion)
    {
        list($api) = $this->getQueryArray();
        $this->url = ($this->ssl === true ? 'https://' : 'http://') . rawurlencode($this->host) . ':' . rawurlencode($this->port) . '/json-api/' . $this->function . '?api.version=' . $apiVersion;
        foreach ($arguments as $key => $value)
        {
            $value     = rawurlencode($value);
            $this->url .= "&{$key}={$value}";
        }
        $this->url = trim($this->url, '&');

        return $this->execRequest();
    }

    private function getQueryArray()
    {
        if ($this->tempout == 'xml')
            return array('xml-api', 'cpanel_xmlapi_user', 'cpanel_xmlapi_module',
                'cpanel_xmlapi_func', 'cpanel_xmlapi_apiversion');
        else
            return array('json-api', 'cpanel_jsonapi_user', 'cpanel_jsonapi_module',
                'cpanel_jsonapi_func',
                'cpanel_jsonapi_apiversion');
    }

    private function execRequest()
    {
        $open_basedir    = ini_get('open_basedir');
        $is_open_basedir = empty($open_basedir) ? 1 : 0;

        $ch        = curl_init();
        $chOptions = array(
            CURLOPT_URL            => $this->url,
            CURLOPT_PORT           => $this->port,
            CURLOPT_SSL_VERIFYPEER => isset($this->curlCustomConfig['CURLOPT_SSL_VERIFYPEER']) ? $this->curlCustomConfig['CURLOPT_SSL_VERIFYPEER'] : 0,
            CURLOPT_SSL_VERIFYHOST => isset($this->curlCustomConfig['CURLOPT_SSL_VERIFYHOST']) ? $this->curlCustomConfig['CURLOPT_SSL_VERIFYHOST'] : 0,
            CURLOPT_RETURNTRANSFER => isset($this->curlCustomConfig['CURLOPT_RETURNTRANSFER']) ? $this->curlCustomConfig['CURLOPT_RETURNTRANSFER'] : 1,
            CURLOPT_FOLLOWLOCATION => isset($this->curlCustomConfig['CURLOPT_FOLLOWLOCATION']) ? $this->curlCustomConfig['CURLOPT_FOLLOWLOCATION'] : $is_open_basedir,
            CURLOPT_TIMEOUT        => isset($this->curlCustomConfig['CURLOPT_TIMEOUT']) ? $this->curlCustomConfig['CURLOPT_TIMEOUT'] : 90,
        );

        if ($this->authtype)
        {
            $header[0]                     = "Authorization: Basic " . base64_encode($this->username . ":" . $this->password);
            $chOptions[CURLOPT_HTTPHEADER] = $header;
        }
        else
        {
            $header[0]                     = 'Authorization: WHM ' . $this->username . ':' . str_replace(array(
                        "\r", "\n"), "", $this->password);
            $chOptions[CURLOPT_HTTPHEADER] = $header;
        }

        curl_setopt_array($ch, $chOptions);
        $result = curl_exec($ch);
        $this->lastResponse =  $result;
        $this->debug();
        if ($result == false)
        {
            if (curl_error($ch) == 'connect() timed out!')
            {
                throw (new LangException('Could not connect to the server. Please try again later or contact administrator.'))->translate();
            }
            throw (new LangException(curl_error($ch)))->translate();
        }
        elseif (!is_object($result) && !is_string($result))
        {
            throw (new LangException(htmlspecialchars($result)))->translate();
        }

        curl_close($ch);
        return $result;
    }

    private function debug()
    {
        if (!$this->loger || !method_exists($this->loger, 'log'))
        {
            return;
        }
         parse_str( $this->url,$data );
        $action = print_r(sprintf("GET: %s\r\n", print_r(   $data, true)), true);
        $this->loger->log($this->host, $action, $this->lastResponse, null);
        
    }
    
    function setLoger($loger)
    {
        $this->loger = $loger;
    }
    
    private function checkIfThereIsNoErrorInResult($result)
    {
        if ($this->tempout == 'xml')
        {
            $obj = json_decode(json_encode(simplexml_load_string($result, null, LIBXML_NOERROR | LIBXML_NOWARNING)));
        }
        else
        {
            $obj = json_decode($result);
        }
        if ($obj->cpanelresult->error)
        {
            throw (new LangException($obj->cpanelresult->error))->translate();
        }
        $errormsg = '';

        switch ($this->api)
        {
            case 'WHM0':
                if (isset($obj->status) && $obj->status == 0) //data request
                    $errormsg = $obj->statusmsg;

                if (isset($obj->result->status) && $obj->result->status == 0) //action
                    $errormsg = $obj->result->statusmsg;
                break;
            case 'WHM1':
                if (isset($obj->metadata->result) && $obj->metadata->result == 0)
                    $errormsg = $obj->metadata->reason;
                break;
            case 'API1':

                if (isset($obj->error))
                    $errormsg = $this->parseFtpErrorForApi1($obj->error);
                break;
            case 'API2':
                if (isset($obj->cpanelresult->event->result) && $obj->cpanelresult->event->result == 1)
                { //if result is '1' API request succeeded
                    return;
                }

                if (isset($obj->cpanelresult->error))
                {
                    $errormsg = $obj->cpanelresult->error;
                }
                if (isset($obj->cpanelresult->data->result) && $obj->cpanelresult->data->result == 0)
                {
                    $errormsg = $obj->cpanelresult->data->reason;
                }
                break;
        }

        if (!empty($errormsg))
        {
            throw (new LangException($errormsg))->translate();
        }
    }

    private function parseFtpErrorForApi1($errMsg)
    {

        $pos = strpos($errMsg, 'The FTP (File Transfer Protocol) user ');
        if ($pos && $pos > 0)
        {
            $errMsg = substr($errMsg, $pos);
        }

        return $errMsg;
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
     * @return CPanel
     */
    public function setQueryuser($queryuser)
    {
        $this->queryuser = $queryuser;
        return $this;
    }


}
