<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 12:57
 */
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Connection;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Helper\ParseQuery;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\ErrorHandler\ErrorParser;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\ErrorHandler\Exceptions\ApiException;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\response;


class Curl
{
    use WhmcsParams;

    private $curl;
    private $ssl;
    private $host;
    private $port;
    private $username;
    private $password;
    protected $rawResponse;

    public function __construct(Models\Connection\Curl $curlModel)
    {
        $this->setParams($curlModel)
            ->initCurl()
            ->setCurlOpts();
    }

    private function setParams(Models\Connection\Curl $curlModel)
    {
        $server = $this->getWhmcsParamsByKeys([
            'serverusername',
            'serverpassword'
        ]);

        $modelUsername =  $curlModel->getUsername();
        $username = $this->isReseller($server['serverusername'], $modelUsername);

        $this->host     = $curlModel->getHostname() ? $curlModel->getHostname() : $curlModel->getIp();
        $this->username =  ($username) ? $username : $server['serverusername'] . '|' . $modelUsername;
        $this->password = $server['serverpassword'];
        $this->port     = $curlModel->getPort();
        $this->ssl      = $curlModel->getSsl();

        if(!$server['serverusername'] || !$server['serverpassword'])
        {
            $this->password = $curlModel->getPassword();
            $this->username = $curlModel->getUsername();
        }

        return $this;
    }

    public function isReseller($serverUsername, $modelUsername)
    {
        if($serverUsername == $modelUsername)
        {
            return $serverUsername;
        }
        return false;
    }

    private function initCurl()
    {
        $this->curl = curl_init();

        return $this;
    }

    private function setCurlOpts()
    {
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER , true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER , false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST , false);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_ENCODING, true);

        return $this;
    }

    private function setDownloadCurlOpts()
    {
        curl_setopt($this->curl, CURLOPT_BUFFERSIZE , 512);
        return $this;
    }

    private function setCurlUrl($url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);

        return $this;
    }

    private function setPostFields($post)
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post);

        return $this;
    }

    private function setCurlHeaders(array $headers)
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);

        return $this;
    }

    private function getProtocol()
    {
        return $this->ssl ? 'https' : 'http';
    }

    private function getLoginDetails()
    {
        return urlencode($this->username) . ':' . urlencode($this->password) . '@';
    }

    private function getBaseUrl()
    {
        return $this->getProtocol() . '://' . $this->getLoginDetails() . $this->host . ':' . $this->port  . '/';
    }

    private function execute()
    {
        return curl_exec($this->curl);
    }

    public function request($command, $post = [], $get = [], $useHttpBuildQuery = false, $skipFilter = false)
    {
        $url = $this->getBaseUrl() . $command;

        if($get)
        {
            $url .= '?';
            foreach ($get as $key => $value)
            {
                $value = urlencode($value);
                $key   = urlencode($key);
                $url .= "{$key}={$value}&";
            }
        }

        if($post)
        {
            if($skipFilter)
            {
                $params = $post;
            }
            else
            {
                $params = array_filter($post, function($element){
                    if (is_object($element))
                    {
                        return true;
                    }
                    return strlen($element);
                });
            }
            $useHttpBuildQuery ? $this->setPostFields(http_build_query($params)) : $this->setPostFields($params);
        }

        $result = $this->setCurlUrl(trim($url,'&'))
                    ->execute();

        $this->rawResponse = $result;

        if($result === false)
        {
            throw new ApiException(curl_error($this->curl));
        }
        if($get['action'] == "json_all" || $get['json'] == "yes" || $post['json'] == 'yes')
        {
            return $this->parseJsonRequest($result);
        }

        $return = (new ParseQuery())->parseInto($result);

        \logModuleCall('DirectAdminExtended', $command, array_merge($post,$get), $return);

        return (new ErrorParser($return, true))->getResponse();
    }

    public function parseJsonRequest($response)
    {
        $result = json_decode($response);


        if(isset($result->error))
        {
            if($result->error !== '0')
            {
               throw new ApiException(empty($result->result) ? $result->error : $result->result );
            }
            return $result;
        }
        return $result;
    }

    public function customRequest($command, $post = [], $get = [], $useHttpBuildQuery = false)
    {
        $url = $this->getBaseUrl() . $command;
        if($get)
        {
            $url .= '?';
            foreach ($get as $key => $value)
            {
                $value = urlencode($value);
                $key   = urlencode($key);
                $url .= "{$key}={$value}&";
            }
        }

        if($post)
        {
            $params = array_filter($post, function($element){
                if (is_object($element))
                {
                    return true;
                }
                return strlen($element);
            });

            $useHttpBuildQuery ? $this->setPostFields(http_build_query($params)) : $this->setPostFields($params);
        }

        return $this->setCurlUrl(trim($url,'&'))
            ->execute();
    }

    public function downloadRequest($command, $get = []){

        $url = $this->getBaseUrl() . $command;

        $url .= '?';

        foreach ($get as $key => $value)
        {
            $value = urlencode($value);
            $key   = urlencode($key);
            $url .= "{$key}={$value}&";
        }

        $result = $this->setDownloadCurlOpts()->setCurlUrl(trim($url,'&'))->execute();
        if($result === false)
        {
            throw new ApiException(curl_error($this->curl));
        }

        return $result;
    }
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

}