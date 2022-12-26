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
    private $cookie;
    protected $rawResponse;

    public function __construct(Models\Connection\Curl $curlModel)
    {
        $this->setParams($curlModel)
            ->initCurl()
            ->setCurlOpts();
    }
    public function login()
    {


        $this->curlHeader = true;
        $hostUrl          = "{$this->protocol}{$this->host}:{$this->port}";
        $loginUrl         = "{$hostUrl}/CMD_LOGIN";
        $login = "{$this->username}";



        $this->post       = [
            'username' => $login,
            'password' => $this->password,
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

    private function setParams(Models\Connection\Curl $curlModel)
    {
        $server = $this->getWhmcsParamsByKeys([
            'serverusername',
            'serverpassword'
        ]);

        $modelUsername =  $curlModel->getUsername();
        $modelRunAsAdmin =  $curlModel->getRunAsAdmin();

        $username = $this->isReseller($server['serverusername'], $modelUsername);

        $this->host     = $curlModel->getHostname() ? $curlModel->getHostname() : $curlModel->getIp();
        if(!$username){
            if($modelRunAsAdmin){
                $username=$server['serverusername'] ;
            }else{
                $username=$server['serverusername'] . '|' . $modelUsername;
            }
        }else{
            if(isset($_SESSION['adminloginas'][$this->getWhmcsParamByKey('serviceid')])){
                $username=$server['serverusername'] . '|' . $_SESSION['adminloginas'][$this->getWhmcsParamByKey('serviceid')];
            }
        }


        $this->username =  $username;
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
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 20);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_ENCODING, true);




        return $this;
    }
    public function setAuthCookie()
    {

        if ($this->cookie)
        {
            // $cookie=explode(";", $this->cookie);
            // $this->cookie=$cookie[0];
            curl_setopt($this->curl, CURLOPT_COOKIESESSION, true);
            curl_setopt($this->curl, CURLOPT_COOKIE, $this->cookie." csfview=mobile");
        }
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
    private function getBaseUrlWithoutAuth()
    {
        return $this->getProtocol() . '://' . $this->host . ':' . $this->port  . '/';
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
            if(isset($params['json'])){
                if((isset($params['action']) && $params['action']=='save') && (isset($params['text']))){
                    $a = htmlentities($params['text']);
                    $b = html_entity_decode($a);

                    $params['text']=str_replace("&#039;", "'",html_entity_decode($b));
                }

                $payload = json_encode($params);

                $this->setPostFields($payload);

                $this->setCurlHeaders(array('Content-Type:application/json'));

            }else{
                $useHttpBuildQuery ? $this->setPostFields(http_build_query($params)) : $this->setPostFields($params);
            }

        }

        $result = $this->setCurlUrl(trim($url,'&'))
                    ->execute();
        $this->rawResponse = $result;
         logModuleCall('DirectAdminExtended1', $command, [$url,$post,$get,curl_getinfo($this->curl)], $result);

        if($result === false)
        {
            throw new ApiException(curl_error($this->curl));
        }
        if($get['action'] == "json_all" || $get['json'] == "yes" || $post['json'] == 'yes')
        {

            return $this->parseJsonRequest($result);
        }

        $return = (new ParseQuery())->parseInto($result);

        // \logModuleCall('DirectAdminExtended1', $command, [$url,$post,$get], $return);

        return (new ErrorParser($return, true))->getResponse();
    }
    public function custombuildrequest($command, $post = [], $get = [], $useHttpBuildQuery = true, $skipFilter = false)
    {
         $url = $this->getBaseUrlWithoutAuth() . $command;
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


                $payload = json_encode($params);
                $this->setPostFields($payload);
                $login              = "{$this->username}:{$this->password}";
                $authLogin = array(
                    'Content-Type:application/json',
                    'Authorization: Basic '. base64_encode($login)
                );
                $this->setCurlHeaders($authLogin);




        }
        $this->setAuthCookie();
        curl_setopt($this->curl, CURLOPT_REFERER, $url);
        $result = $this->setCurlUrl(trim($url,'&'))
                    ->execute();
        $this->rawResponse = $result;
         \logModuleCall('DirectAdminExtended1', $command, [$url,$post,$get,curl_getinfo($this->curl)], $result);

        if($result === false)
        {
            throw new ApiException(curl_error($this->curl));
        }
        if($get['action'] == "json_all" || $get['json'] == "yes" || $post['json'] == 'yes')
        {

            return $this->parseJsonRequest($result);
        }
        return $result;
    }
    public function getApiUrl($command, $post = [], $get = [], $useHttpBuildQuery = false, $skipFilter = false)
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
            } else{
                $params = array_filter($post, function($element){
                    if (is_object($element))
                    {
                        return true;
                    }
                    return strlen($element);
                });
            }
            if(isset($params['json'])){
                if((isset($params['action']) && $params['action']=='save') && (isset($params['text']))){
                    $a = htmlentities($params['text']);
                    $b = html_entity_decode($a);

                    $params['text']=str_replace("&#039;", "'",html_entity_decode($b));
                }

                $payload = json_encode($params);

                $this->setPostFields($payload);

                $this->setCurlHeaders(array('Content-Type:application/json'));

            }else{
                $useHttpBuildQuery ? $this->setPostFields(http_build_query($params)) : $this->setPostFields($params);
            }
        }

       return trim($url,'&');
    }

    function handleStream($ch, $data){

      // how big is the data transmission
      $bytes = strlen($data);

      static $buf = '';
      $buf .= $data;

      // Collect the details of each transmission
      $info = curl_getinfo($this->curl);
      $http_code = $info['http_code'];
      $total_time = $info['total_time'];
      $namelookup_time = $info['namelookup_time'];
      $connect_time = $info['connect_time'];
      $size_download = $info['size_download'];
      $speed_download = $info['speed_download'];
      $download_content_length = $info['download_content_length'];

      while(1)
        {

        $pos = strpos($buf, "\n");
        if($pos === false)
          {
          break;
          }

        // trim things down
        $data = substr($buf, 0, $pos+1);
        $buf = substr($buf, $pos+1);

        // only log if there is something there
        if(strlen($data)>50)
          {

          // remove data: prefix
          $results = str_replace("data:","",$data);
        file_put_contents('/var/www/html/cyberpower/downloads/test.txt', $results.PHP_EOL , FILE_APPEND | LOCK_EX);

          // Log the details of the transaction to Amazon S3 (or other)
          // Log the content of the transaction to Amazon S3 (or other)

          }
        }

      // this is important!
      // won't run if we don't return exact size
      return $bytes;
  }
  public function setWriteHeader()
  {
      curl_setopt($this->curl, CURLOPT_WRITEFUNCTION, [$this, 'handleStream']);
      return $this;

  }
    public function streamrequest($command, $post = [], $get = [], $useHttpBuildQuery = false, $skipFilter = false)
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
            if(isset($params['json'])){
                if((isset($params['action']) && $params['action']=='save') && (isset($params['text']))){
                    $a = htmlentities($params['text']);
                    $b = html_entity_decode($a);

                    $params['text']=str_replace("&#039;", "'",html_entity_decode($b));
                }

                $payload = json_encode($params);

                $this->setPostFields($payload);

                $this->setCurlHeaders(array('Content-Type:application/json'));

            }else{
                $useHttpBuildQuery ? $this->setPostFields(http_build_query($params)) : $this->setPostFields($params);
            }

        }
        $this->setWriteHeader();
        $result = $this->setCurlUrl(trim($url,'&'))
                    ->execute();
        $this->rawResponse = $result;
         // \logModuleCall('DirectAdminExtended1', $command, [$url,$post,$get,curl_getinfo($this->curl)], $result);

        if($result === false)
        {
            throw new ApiException(curl_error($this->curl));
        }
        if($get['action'] == "json_all" || $get['json'] == "yes" || $post['json'] == 'yes')
        {

            return $this->parseJsonRequest($result);
        }

        $return = (new ParseQuery())->parseInto($result);

        // \logModuleCall('DirectAdminExtended1', $command, [$url,$post,$get], $return);

        return (new ErrorParser($return, true))->getResponse();
    }
    public function cookierequest($command, $post = [], $get = [], $useHttpBuildQuery = true, $skipFilter = false)
    {
         $url = $this->getBaseUrlWithoutAuth() . $command;
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
            if(isset($params['json'])){
                if((isset($params['action']) && $params['action']=='save') && (isset($params['text']))){
                    $a = htmlentities($params['text']);
                    $b = html_entity_decode($a);

                    $params['text']=str_replace("&#039;", "'",html_entity_decode($b));
                }

                $payload = json_encode($params);

                $this->setPostFields($payload);
                $login              = "{$this->username}:{$this->password}";
                $authLogin = array(
                    'Content-Type:application/json',
                    'Authorization: Basic '. base64_encode($login)
                );
                $this->setCurlHeaders($authLogin);

            }else{
                $useHttpBuildQuery ? $this->setPostFields(http_build_query($params)) : $this->setPostFields($params);
                $login              = "{$this->username}:{$this->password}";
                $authLogin = array(
                    'Content-Type:application/x-www-form-urlencoded',
                    'Authorization: Basic '. base64_encode($login)
                );
                $this->setCurlHeaders($authLogin);
            }


        }
        $this->setAuthCookie();
        curl_setopt($this->curl, CURLOPT_REFERER, $url);
        $result = $this->setCurlUrl(trim($url,'&'))
                    ->execute();
        $this->rawResponse = $result;
         \logModuleCall('DirectAdminExtended1', $command, [$url,$post,$get,curl_getinfo($this->curl)], $result);

        if($result === false)
        {
            throw new ApiException(curl_error($this->curl));
        }
        if($get['action'] == "json_all" || $get['json'] == "yes" || $post['json'] == 'yes')
        {

            return $this->parseJsonRequest($result);
        }
        return $result;
    }

    public function parseJsonRequest($response)
    {
        $result = json_decode($response);


        if(isset($result->error))
        {
            if($result->error !== '0' && !isset($result->FILEDATA))
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
