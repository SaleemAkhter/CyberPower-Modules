<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Jan 16, 2018)
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

namespace ModulesGarden\WordpressManager\App\Modules;

use ModulesGarden\WordpressManager\App\Interfaces\WordPressModuleInterface;
use ModulesGarden\WordpressManager\App\Helper\UtilityHelper;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Modules\Softaculous\DirectAdminProvider;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use ModulesGarden\WordpressManager\App\Modules\DirectAdmin\DirectAdminApi;
use \ModulesGarden\WordpressManager\App\Modules\DirectAdmin\User;
/**
 * Description of Directadmin
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class Directadmin implements WordPressModuleInterface
{
    protected $params;
    protected $softaculous;
    protected $api;
    protected $apiAsuser;
    protected $debugMode;
    private $username;
    protected $domainService;
    public $loggedinusername;

    public function setParams(array $params)
    {
        if (!$params['username'])
        {
            throw new \Exception("API: Username is empty");
        }
        if (!$params['serverusername'])
        {
            throw new \Exception("API: server\'s username  is empty");
        }
        if (!$params['serverpassword'])
        {
            throw new \Exception("API: server\'s password  is empty");
        }
        if (!$params['serverip'] && !$params['serverhostname'])
        {
            throw new \Exception("API: server\'s ip or host is empty");
        }
        $this->params = $params;

        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }

    /**
     * 
     * @return Softaculous
     */
    public function softaculous()
    {
        if (!is_null($this->softaculous))
        {
            return $this->softaculous;
        }

        $host     = !empty($this->params['serverhostname']) ? $this->params['serverhostname'] : $this->params['serverip'];
        $port     = !empty($this->params['serverport']) ? $this->params['serverport'] : '2222';
        $protocol = ($this->params['serversecure']) ? 'https://' : 'http://';
        $username = $this->params['username'];
        if($this->username){
            $username = $this->username;
        }
        $provider = new DirectAdminProvider($protocol, $host, $port, $this->params['serverusername'], $this->params['serverpassword'], $username);
        if ($this->debugMode)
        {
            $provider->setLoger(new \ModulesGarden\WordpressManager\App\Helper\Loger('WpSoftaculous'));
        }
        $provider->login();
        return $this->softaculous = new Softaculous\Softaculous($provider);
    }

    public function setDebugMode($debugMode)
    {
        $this->debugMode = $debugMode;
        return $this;
    }

    /**
     * 
     * @return DirectAdminApi
     */
    public function api()
    {
        if (!is_null($this->api))
        {
            return $this->api;
        }
        $host      = !empty($this->params['serverhostname']) ? $this->params['serverhostname'] : $this->params['serverip'];
        $port      = !empty($this->params['serverport']) ? $this->params['serverport'] : '2222';
        $protocol  = ($this->params['serversecure']) ? 'https://' : 'http://';
        $this->api = new DirectAdminApi($protocol, $host, $port, $this->params['serverusername'], $this->params['serverpassword']);
        $this->api->setAdminUsername($this->params['serverusername'] );
        return $this->api;
    }

    /**
     * 
     * @return DirectAdminApi
     */
    public function apiAsUser()
    {

        if (!is_null($this->apiAsuser))
        {
            return $this->apiAsuser;
        }
        $host            = !empty($this->params['serverhostname']) ? $this->params['serverhostname'] : $this->params['serverip'];
        $port            = !empty($this->params['serverport']) ? $this->params['serverport'] : '2222';
        $protocol        = ($this->params['serversecure']) ? 'https://' : 'http://';
        $username = $this->params['serverusername']."|".$this->params['username'];
        if($this->username){
            $username = urlencode($this->params['serverusername'])."|".$this->username;
        }
        $this->loggedinusername=$username;
        $this->apiAsuser = new DirectAdminApi($protocol, $host, $port, $username,$this->params['serverpassword']);
        if ($this->debugMode)
        {
             $this->apiAsuser->setLoger(new \ModulesGarden\WordpressManager\App\Helper\Loger('WpDirectAdminApi'));
        }
        $this->apiAsuser->setAdminUsername($this->params['serverusername'] );
        return $this->apiAsuser;
    }

    public function getInstallations($appIds=[])
    {

        if(empty($appIds)){
            $app = $this->softaculous()->getWordPressInstallationScript();
            $appIds[]=$app['id'];
        }
        $data             = $this->softaculous()->getInstallations();
        $collection       = [];
        foreach ($data['installations'] as $apId => $Installations)
        {
            if ($apId != in_array($apId, $appIds))
            {
                continue;
            }
            foreach ($Installations as $id => $Installation)
            {
                $collection[] = [
                    'id'      => $id,
                    'domain'  => $Installation['softdomain'],
                    'url'     => $Installation['softurl'],
                    'path'    => $Installation['softpath'],
                    'version' => $Installation['ver'],
                    'staging' => $Installation['is_staging'],
                    'site_name'=>$Installation['site_name'],
                    'db' => $Installation['softdb'],
                    'dbHost' => $Installation['softdbhost'],
                    'dbUser' => $Installation['softdbuser'],
                    'dbPass' => $Installation['softdbpass'],
                ];
            }
        }
        return $collection;
    }

    public function installationCreate(array $data)
    {
        if($data['installationScript']){
            $app['id'] = $data['installationScript'];
            unset($data['installationScript']);
        }else{
            $app = $this->softaculous()->getWordPressInstallationScript();
        }
        return $this->softaculous()->installationCreate($app['id'], $data);
    }

    public function installationDelete($id, array $data)
    {
        $post=['noemail'=>1];
        if($data['directoryDelete']=="on"){
            $post['remove_dir']=1;
            $post['remove_datadir']=1;
        }
        if($data['databaseDelete']=="on"){
            $post['remove_db']=1;
            $post['remove_dbuser']=1;
        }
        return $this->softaculous()->installationDelete($id,$post);
    }
    public function getWpFileList($response)
    {
        $path=explode("domains",$response['userins']['softpath']);
        $mainpath="/domains".$path[1];
        return $this->apiAsUser()->getFileList($mainpath);
    }
    public function installationDetail($installationId)
    {
        if(isset($_SESSION['installationDetails'],$_SESSION['installationDetails']['expire'],$_SESSION['installationDetails']['expire'][$installationId]) && time() < $_SESSION['installationDetails']['expire'][$installationId]){
            return $_SESSION['installationDetails'][$installationId];
        }


        $response  = $this->softaculous()->installationDetail($installationId);

        $colection = [
            'domain'   => $response['userins']['softdomain'],
            'siteName' => $response['userins']['site_name'],
            'version'  => $response['userins']['ver'],
            'path'     => $response['userins']['softpath'],
            'url'      => $response['userins']['softurl'],
            'db'       => $response['userins']['softdb'],
            'dbHost'   => $response['userins']['softdbhost'],
            'dbUser'   => $response['userins']['softdbuser'],
            'dbPass'   => $response['userins']['softdbpass'],

            'auto_backup'   => $response['userins']['auto_backup'],
            'auto_backup_rotation'   => $response['userins']['auto_backup_rotation'],
            'auto_backup_crontime'   => $response['userins']['auto_backup_crontime'],
            'auto_backup_command'   => $response['userins']['auto_backup_command'],
            'disable_notify_update'   => $response['userins']['disable_notify_update'],
            'auto_upgrade_plugins'   => $response['userins']['auto_upgrade_plugins'],
            'auto_upgrade_themes'   => $response['userins']['auto_upgrade_themes'],
            'blog_public'   => $response['userins']['blog_public'],
        ];
        $d=$this->getWpFileList($response);
        $e=json_decode($d,true);
        $additionalFiles=[];
        $path=explode("domains",$response['userins']['softpath']);
        $mainpath="/domains".$path[1];
        if(!empty($e)){
            unset($e['info']);
            foreach ($e as $file => $info) {
                $f=str_replace($mainpath."/", '', $file);
                if(!in_array($f, $response['userins']['fileindex'])){
                    $additionalFiles[$f]=$info;
                }
            }
        }
        $response['add_to_fileindex']=$additionalFiles;
        $colection = array_merge($colection, $response);
        $_SESSION['installationDetails'][$installationId]=$colection;
        $_SESSION['installationDetails']['expire'][$installationId]=time()+2000;
        return $colection;
    }

    public function getBackups($InstallationId)
    {
        $collection = [];
        foreach ($this->softaculous()->getBackups($InstallationId) as $apId => $instaltions)
        {
            foreach ($instaltions as $instId => $backups)
            {
                foreach ($backups as $k => $backup)
                {
                    $collection[] = [
                        'id'             => $backup['name'],
                        'installationId' => $instId,
                        'name'           => $backup['name'],
                        'note'           => $backup['backup_note'],
                        'path'           => $backup['path'],
                        'size'           => UtilityHelper::formatBytes($backup['size']),
                        'bytes'          => $backup['size'],
                        'version'        => $backup['ver'],
                        'timestamp'      => $backup['btime'],
                        'date'           => date("Y-m-d H:i:s", $backup['btime'])
                    ];
                }
            }
        }
        return $collection;
    }

    /**
     * 
     * @param array $data['installationId'] - The Installation ID that you want to clone. It can be fetched from List Installed Script
     * @param array $data['backupDirectory'] 
     * @param array $data['backupDataDir'] 
     * @param array $data['backupDatabase']
     * @param array $data['note']
     * @return array
     */
    public function backupCreate(array $data)
    {
        $request  = [
            'backup_dir'     => $data['backupDirectory'],
            'backup_datadir' => $data['backupDataDir'],
            'backup_db'      => $data['backupDatabase'],
            'backup_note'=> $data['backup_note'],
        ];
        $response = $this->softaculous()->backupCreate($data['installationId'], $request);
    }

    public function backupRestore($backupId, Installation $installation)
    {
        return $this->softaculous()->backupRestore($backupId);
    }

    public function backupDelete($fileName, Installation $installation)
    {
        return $this->softaculous()->backupDelete($fileName);
    }

    /**
     * 
     * @param array $data['installationId'] The Installation ID that you want to clone. It can be fetched from List Installed Script
     * @param array $data['domain'] This is the domain on which you wish to clone the installation
     * @param array $data['directory'] This is the sub-directory to clone the installation in. Leave it blank to clone in root of the domain
     * @param array $data['db']
     * @return array
     */
    public function installationClone(array $data)
    {
        $request = [
            'softsubmit'    => 1,
            'softdomain'    => $data['softdomain'],
            'softdirectory' => $data['softdirectory'],
            'softdb'        => $data['softdb'],
        ];
        return $this->softaculous()->installationClone($data['installationId'], $request);
    }

    public function installationUpgrade($installationId)
    {
        return $this->softaculous()->installationUpgrade($installationId);
    }

    public function getSingleSignOnUrl($installationId)
    {
        $response = $this->softaculous()->signOn($installationId);
        return $response['sign_on_url'];
    }

    public function cacheFlush(Installation $installation)
    {
        $response = $this->apiAsUser()->wp($installation->path)->cache()->flush();
        return $response;
    }

    public function backupDownload($backupId, Installation $installation)
    {
        return $this->softaculous()->backupDownload($backupId);
    }

    /**
     * 
     * @param array $path
     * @return array $collection[][id]
     * @return array $collection[][name]
     * @return array $collection[][status]
     * @return array $collection[][update]
     * @return array $collection[][version]
     */
    public function getPlugins(Installation $installation)
    {
        $collection = [];
        $response   = $this->apiAsUser()->wp($installation->path)->plugin()->getList();
        foreach ($response as $plugin)
        {
            $row = [
                   "installationId"=>$installation->id,
                   "id"=> base64_encode(json_encode($plugin))
            ];
            $collection[] = array_merge( $row,$plugin);
        }
        return $collection;
    }

    /**
     *
     * @param array $path
     * @return array $collection[][id]
     * @return array $collection[][name]
     * @return array $collection[][status]
     * @return array $collection[][update]
     * @return array $collection[][version]
     */
    public function getThemes(Installation $installation)
    {
        $collection = [];
        $response   = $this->apiAsUser()->wp($installation->path)->theme()->getList();
        foreach ($response as $theme)
        {
            $row = [
                "installationId"=>$installation->id,
                "id"=> base64_encode(json_encode($theme))
            ];
            $collection[] = array_merge( $row,$theme);
        }
        return $collection;
    }

    public function pluginActivate(Installation $installation, $pluginName)
    {
        return $this->apiAsUser()->wp($installation->path)->plugin()->activate($pluginName);
    }

    public function pluginDeactivate(Installation $installation, $pluginName)
    {
        return $this->apiAsUser()->wp($installation->path)->plugin()->deactivate($pluginName);
    }

    public function pluginUpdate(Installation $installation, $pluginName)
    {
        return $this->apiAsUser()->wp($installation->path)->plugin()->update($pluginName);
    }
    public function pluginUpdateAll(Installation $installation)
    {
        return $this->apiAsUser()->wp($installation->path)->plugin()->updateAll();
    }
    public function pluginUpload(Installation $installation, $formData)
    {
        $request = [
            'type'=> 'plugins',
            'insid'=> $formData['insid'],
            'activate'=> 1,
            'custom_file'=> $formData['custom_file'],
        ];
        return $this->softaculous()->pluginUpload($request);
        return $this->apiAsUser()->wp($installation->path)->plugin()->activate($pluginName);
    }
    public function themeUpdateAll(Installation $installation)
    {
        return $this->apiAsUser()->wp($installation->path)->theme()->updateAll();
    }
    public function getConfig(Installation $installation)
    {
        $collection = [];
        return $this->apiAsUser()->wp($installation->path)->config();
    }

    public function domainCreate(array $data)
    {
        $request = [
            'domain'    => $data['newDomain'],
            'bandwidth' => $data['bandwidth'] == '0' ? 'unlimited' : $data['bandwidth'],
            'quota'     => $data['quota'] == '0' ? 'unlimited' : $data['quota'],
            'php'       => 'on'
        ];
        return $this->apiAsUser()->addAddonDomain($request);
    }

    public function domainInfo()
    {
        
    }

    public function getAddonDomains()
    {
        $out = [];
        $user = $this->params['username'];
        if($this->username){
             $user = $this->username;
        }
        foreach ($this->api()->getAddonDomains($user) as $k => $v)
        {
            if ($this->params['domain'] == $k)
            {
                continue;
            }
            $out[] = ['domain' => $k];
        }
        return $out;
    }

    /**
     * @deprecated since version 1.3.0
     */
    public function changeDomain(Installation $installation, $data)
    {
        return $this->apiAsUser()->changeDomain($installation->domain, $data['newDomain']);

    }

    public function getChangeDomainFields(Installation $installation)
    {
        $fields   = [];
        $fields[] = new Fields\Text('newDomain');
        return $fields;
    }

    public function pluginInstall(Installation $installation, $pluginName,$activate=false)
    {
        $this->username=$installation->username;
        $appuser=$this->apiAsUser();
        return $appuser->wp($installation->path)->plugin()->install($pluginName,$activate);
    }
    
    public function getInstallationScript()
    {
        return $this->softaculous()->getWordPressInstallationScript();
    }
    
    public function pluginSearch(Installation $installation, $name)
    {
        $collection = [];

        if($installation->username)
        {
            $this->setUsername($installation->username);
        }
        $response   =  $this->apiAsUser()->wp($installation->path)->plugin()->search($name);
        foreach ($response as $plugin)
        {
            $row = [
                   "installationId"=>$installation->id,
                   "id"=> base64_encode(json_encode($plugin))
            ];
            $collection[] = array_merge( $row,$plugin);
        }
        return $collection;
    }
    /**
     * 
     * @param Installation $installation
     * @return \ModulesGarden\WordpressManager\App\Interfaces\WordPressPluginInterface
     */
    public function getPlugin(Installation $installation)
    {
        return $this->apiAsUser()->wp($installation->path)->plugin();
    }

    public function getInstallationScripts()
    {
        return $this->softaculous()->getWordPressInstallationScripts();
    }
    public function autodetectWp(array $data)
    {
        return $this->softaculous()->autodetectWp( $data);
    }
    public function installationUpdateSiteDetails($id,array $data)
    {
        return $this->softaculous()->installationUpdateSiteDetails($id, $data);
    }
    public function installationUpdateOld($id,array $data)
    {
        return $this->softaculous()->installationUpdateOld($id, $data);
    }

    public function installationUpdate($id,array $data)
    {
        return $this->softaculous()->installationUpdate($id, $data);
    }
    
    public function getTheme(Installation $installation)
    {
        if($installation->username)
        {
            $this->setUsername($installation->username);
        }

        return $this->apiAsUser()->wp($installation->path)->theme();
        
    }

    public function getWpCli(Installation $installation)
    {
        return $this->apiAsUser()->wp($installation->path);
    }
    
    public function installation(Installation $installation)
    {
        return  (new Softaculous\Installation($installation))->setSoftaCulous($this->softaculous());
    }
    
    public function import(array $post)
    {
        $post['remote_submit'] = "Import";
        $soft               = $post['soft'];
        unset($post['soft']);
        return $this->softaculous()->setGet(['act' => 'import', 'soft' => $soft, 'api' => 'json'])
                                   ->setPost($post)
                                   ->sendRequest();
    }
    public function importProgress(array $post)
    {
        $soft               = $post['soft'];
        $decimals = 10; // number of decimal places
        $div = pow(10, $decimals);

        // Syntax: mt_rand(min, max);
        $random=mt_rand(0.01 * $div, 0.05 * $div) / $div;
        $ajaxstatus=$post['ref'];
        unset($post['soft']);
        $response= $this->softaculous()->setGet(['act' => 'import', 'soft' => 26,'random'=>$random ,'ajaxstatus' => $ajaxstatus])->sendRequest();
        $response=explode("|",$response);
        if(count($response)==3){
            return [
                'progress'=>$response[0],
                'message'=>$response[1],
                'info'=>json_decode($response[2])
            ];
        }elseif(count($response)==2){
            return [
                'progress'=>$response[0],
                'message'=>$response[1],
            ];
        }
        return $response;
    }

    /**
     * 
     * @param Installation $installation
     * @return \ModulesGarden\WordpressManager\App\Interfaces\SslInterface
     */
    public function ssl()
    {
        return new DirectAdmin\Ssl( $this->apiAsUser());
         
    }

    public function domain()
    {
        if($this->domainService){
            return $this->domainService;
        }
        $username = $this->username ?  $this->username : $this->params['username'];
        $this->domainService = new DirectAdmin\Domain( $this->apiAsUser());
        $this->domainService->setAttributes(['username' => $username]);
        return $this->domainService;
    }

    public function isSupportChangeDomain()
    {
        return true;
    }
    
    public function reseller(){
        return new DirectAdmin\Reseller($this->apiAsUser());
    }

    public function setUsername($username)
    {
        $this->username = $username;
        unset($this->softaculous, $this->apiAsuser);
        return $this;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
}
