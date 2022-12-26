<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\Core\ModuleConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\RequestObjectHandler;

class SSO
{

    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\HostingComponent;
    use RequestObjectHandler;

    protected $productID;

    public function __construct($productID)
    {
        $this->productID = $productID;
        $this->initHosting($productID);
    }

    public function getWebmailURL()
    {
        $serverDetails = $this->getServerDetails();
        return $serverDetails['protocol'] . $serverDetails['hostname'] . '/'. $this->getMailClient();
    }

    public function getCorrectRedirection($target)
    {
        switch($target)
        {
            case 'phpmyadmin':
            return $this->getRedirectToPhpMyAdmin();
            case 'webmail':
            header('Location: ' . $this->getWebmailURL());
            exit();
            case 'sitepad':
            return $this->getRedirectToSitePad();    
            default:
            return $this->getRedirectToDirectAdmin();
        }
    }

    public function getLocalLink($page)
    {
        $params = [
            'action'        => 'productdetails',
            'id'            => $this->getRequestValue('id'),
            'modop'         => 'custom',
            'a'             => 'management',
            'mg-page'       => 'OneClickLogin',
            'redirect-action'     => $page
        ];


        return 'clientarea.php?' . http_build_query($params);

    }

    protected function getLoginDetails()
    {
        return [
            'username'  =>   $this->hosting->username,
            'password'  =>  \decrypt($this->hosting->password)
        ];
    }
    public function getRedirectToDirectAdmin()
    {
        $data = $this->getLoginDetails();
        $data['url'] = $this->getDirectAdminURL();

        return $data;
    }
    public function doCurl($url, $referer = '', $cookie = '', $post = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if($referer != '')
        {
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        }
        if(!empty($cookie)){
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        if($post){
            curl_setopt($ch, CURLOPT_POST, 1);
            $nvpreq = http_build_query($post);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
        }
        if($post != []){
            curl_setopt($ch, CURLOPT_HEADER, 1);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $resp = curl_exec($ch);

        curl_close($ch);
        return $resp;
    }
    public function DALogin($login)
    {
        $serverdetails = $this->getServerDetails();

        $sitepad_login = $serverdetails['protocol'].$serverdetails['hostname'].':'. $serverdetails['port'].'/CMD_PLUGINS/sitepad/index.raw?api=serialize';
        $post = array('username' => $login['username'],
            'password' => $login['password'],
            'referer' => '/');

        $resp = $this->doCurl($serverdetails['protocol'].$serverdetails['hostname'].':'.$serverdetails['port'].'/CMD_LOGIN', '', '', $post);

        if($resp === false){
            return false;
        }
        
        $resp = explode("\n", $resp);
        
        // Find the cookies
        foreach($resp as $k => $v){
            if(preg_match('/^'.preg_quote('set-cookie:', '/').'(.*?)$/is', $v, $mat)){
                $sitepad_cookie = trim($mat[1]);
            }
        }

        return $sitepad_cookie;
        
    }
    public function readSitePadLink($url, $daCookie)
    {
        $serverdetails = $this->getServerDetails();
        $daMainUrl = $serverdetails['protocol'].$serverdetails['hostname'].':'.$serverdetails['port'];

        $resp = $this->doCurl($url, $daMainUrl.'/CMD_PLUGINS/', $daCookie);

        if($resp)
        {
            $unserialized = unserialize($resp);
            return $unserialized['redirect_url'];
        }

    }
    public function getRedirectToSitePad()
    {
        $login = $this->getLoginDetails();

        $daCookie = $this->DALogin($login);
        $url = $this->getSitePadURL($login);

        $link = $this->readSitePadLink($url, $daCookie);

        if($link)
        {
            $data['url'] = $link;
        }
        else
        {
            $serverdetails = $this->getServerDetails();
            $daMainUrl = $serverdetails['protocol'].$serverdetails['hostname'].':'.$serverdetails['port'];
            $data['url'] = $daMainUrl . '/CMD_PLUGINS/sitepad/index.raw';
        }
        return $data;
    }
    public function getRedirectToPhpMyAdmin()
    {
        $data = $this->getLoginDetails();
        $data['url'] = $this->getPhpMyAdminURL();

        return $data;
    }

    private function getPhpMyAdminURL()
    {
        $serverDetails = $this->getServerDetails();

        return $serverDetails['protocol'] . $serverDetails['hostname'] . '/phpmyadmin/index.php';
    }

    private function getDirectAdminURL(){
        $serverDetails = $this->getServerDetails();

        return $serverDetails['protocol'] . $serverDetails['hostname'] . ':' . $serverDetails['port'] .'/CMD_LOGIN';
    }
    private function getSitePadURL(array $login){
        $serverDetails = $this->getServerDetails();

        return $serverDetails['protocol'] . $serverDetails['hostname'] . ':' . $serverDetails['port'] .'/CMD_PLUGINS/sitepad/index.raw?api=serialize';
    }
    private function getServerDetails(){
        $serverParams = ServerParams::getServerParamsByHostingId($this->productID);

        return[
            'protocol' => $serverParams['serversecure'] ? 'https://' : 'http://',
            'hostname' => $serverParams['serverhostname'] ? $serverParams['serverhostname'] : $serverParams['serverip'],
            'port'     => $serverParams['serverport'] ? $serverParams['serverport'] : 2222,
        ];
    }

    private function getMailClient(){
       return WebmailAdapter::getClient($this->getFeaturesSettings(FeaturesSettingsConstants::WEBMAIL_TYPE));
   }

   public function getAdminTemplateToLoginLink()
   {
                $info = sl('smarty')
                ->setLang(sl('lang'))
                ->view('loginLink', [
                    'loginLink' => $this->getDirectAdminURL(),
                    'username' => $this->hosting->username,
                    'password' => \decrypt($this->hosting->password),
                ], ModuleConstants::getFullPath('app', 'UI', 'Admin', 'Templates', 'loginLink'));

                return $info;
    }

}
