<?php

namespace ModulesGarden\DirectAdminExtended\App\Services;

// toDO: total refactor this "SERVICE" :D
// leave only getScripts method here
class InstallScriptsService
{

    private $params;
    private $installer   = null;
    private $url;
    private $curl;

    const COOKIE_JAR           = 'cookies.txt';
    const AVAILABLE_INSTALLERS = [
        'softaculous',
        'installatron'
    ];

    public static function init(array $params)
    {
        return new self($params);
    }

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function setInstaller($installer)
    {
        if (!in_array(strtolower($installer), self::AVAILABLE_INSTALLERS))
        {
            throw new \Exception(sprintf('%s installer is invalid', $installer));
        }
        $this->installer = strtolower($installer);

        return $this;
    }

    public function getScripts()
    {
        $this->initSession()
            ->setBasicUrl();

        $this->{$this->installer . 'Url'}();
        $this->setCurlUrl($this->url);
        $result            = $this->execute();
        $this->checkCurl($result);
        $response          = $this->decode($this->execute());
        $installerResponse = $this->parseResponse($response);
        asort($installerResponse);

        return $installerResponse;
    }

    private function checkCurl($result)
    {
        if ($result === false)
        {
            throw new \Exception(sprintf('cURL Error : %s', curl_error($this->curl)));
        }
    }

    private function setBasicUrl()
    {
        $protocol = ($this->params['serversecure'] === 'on' || $this->params['serversecure'] === true) ? 'https' : 'http';
        $host     = $this->params['serverhostname'] ? $this->params['serverhostname'] : $this->params['serverip'];
        $port     = $this->params['serverport'] ? $this->params['serverport'] : 2222;
        $login    = urlencode($this->params['serverusername']) . ':' . urlencode($this->params['serverpassword']);

        $this->url = $protocol . '://' . $login . '@' . $host . ':' . $port;
        return $this;
    }

    private function softaculousUrl()
    {
        $this->url .= '/CMD_PLUGINS/softaculous/index.raw?api=json&act=home';

        return $this;
    }

    private function installatronUrl()
    {
        $this->url .= '/CMD_PLUGINS/installatron/index.raw?cmd=browser&api=json';

        return $this;
    }

    private function initCurl()
    {
        $this->curl = curl_init();

        return $this;
    }

    private function setCurlOpts()
    {
        curl_setopt($this->curl, CURLOPT_VERBOSE, 1);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($this->curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, realpath(dirname(__FILE__) . self::COOKIE_JAR));
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, realpath(dirname(__FILE__) . self::COOKIE_JAR));

        return $this;
    }

    private function setCurlUrl()
    {
        curl_setopt($this->curl, CURLOPT_URL, $this->url);

        return $this;
    }

    private function setCurlPostFields()
    {
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($this->post));

        return $this;
    }

    private function initSession()
    {
        $this->initCurl()
            ->setCurlOpts()
            ->setCurlUrl();

        return $this;
    }

    private function decode($json)
    {
        return json_decode($json);
    }

    private function execute()
    {
        return curl_exec($this->curl);
    }

    protected function parseResponse($scripts)
    {
        if($this->installer == "installatron") {
            return $this->parseInstallatronResponse($scripts);
        }

        return $this->parseSoftaculousResponse($scripts);
    }

    protected function parseInstallatronResponse($scripts){
        $return = [];
        foreach ($scripts->data as $script)
        {
            $return[$script->name] = [
                'name'      => $script->name,
                'version'   => $script->ver
            ];
        }
        return $return;
    }
    protected function parseSoftaculousResponse($scripts){
        $return = [];
        foreach ($scripts->iscripts as $script)
        {
            $return[$script->name] = [
                'name'      => $script->name,
                'version'   => $script->ver
            ];
        }
        return $return;
    }

}
