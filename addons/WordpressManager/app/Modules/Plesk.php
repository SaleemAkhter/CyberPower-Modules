<?php
/* * ********************************************************************
 * Wordpress_Manager Product developed. (Jul 15, 2019)
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


use ModulesGarden\WordpressManager\App\Helper\LangException;
use ModulesGarden\WordpressManager\App\Helper\UtilityHelper;
use ModulesGarden\WordpressManager\App\Interfaces\InstallationInterface;
use ModulesGarden\WordpressManager\App\Interfaces\ThemeInterface;
use ModulesGarden\WordpressManager\App\Interfaces\WordPressConfigInterface;
use ModulesGarden\WordpressManager\App\Interfaces\WordPressModuleInterface;
use ModulesGarden\WordpressManager\App\Interfaces\WordPressPluginInterface;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\Modules\Plesk\ApiClientFactory;
use ModulesGarden\WordpressManager\App\Modules\Plesk\ApiSessionFactory;
use ModulesGarden\WordpressManager\App\Modules\Plesk\ClientFactory;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Domain;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Factory;
use ModulesGarden\WordpressManager\App\Modules\Plesk\GetDomainListFactory;
use ModulesGarden\WordpressManager\App\Modules\Plesk\RestFullFactory;
use ModulesGarden\WordpressManager\App\Modules\Plesk\SoftaculousFactory;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Ssl;
use ModulesGarden\WordpressManager\App\Modules\Plesk\WpCliFactory;
use function ModulesGarden\WordpressManager\Core\Helper\sl;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Text;

class Plesk implements WordPressModuleInterface
{
    protected $params;


    public function setParams(array $params)
    {
        if (!$params['username'])
        {
            throw (new LangException("API: Username is empty"))->translate();
        }
        if (!$params['serverpassword'] && !$params['serveraccesshash'])
        {
            throw (new LangException("API: Username's password is empty"))->translate();
        }
        if (!$params['serverip'] && !$params['serverhostname'])
        {
            throw (new LangException("API: server's ip or host is empty"))->translate();
        }
        $this->params = $params;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }



    public function getInstallationScripts()
    {
        return SoftaculousFactory::fromParamsAsUser($this->params)->getInstallationScripts();
    }

    /**
     * Get WordPress Installations
     * @return array  wp Installations
     */
    public function getInstallations($appIds = [])
    {
        if (empty($appIds))
        {
            $appIds[] = 26;
        }
        $collection = [];
        $softaculous  = SoftaculousFactory::fromParamsAsUser($this->params);
        foreach (GetDomainListFactory::fromParams($this->params)->domains->domain as $entity){
            if($entity->name && $this->params['domain'] !=$entity->name  && Hosting::where('domainstatus','Active')->where('domain',$entity->name )->count()){
                continue;
            }
            $softaculous->getProvider()->setCookieSoftdomId((string)$entity->id);
            $response = $softaculous->getInstallations();
            foreach ( $response['installations'] as $apId => $installations)
            {

                foreach ($installations as $id => $installation)
                {
                    $softId = explode("_", $id);
                    $softId = $softId[0];
                    if (!in_array($softId, $appIds))
                    {
                        continue;
                    }
                    if((string)$entity->id &&  $entity->name  && $installation['softdomain'] != $entity->name ){
                        continue;
                    }
                    $collection[] = [
                        'id'      => $id,
                        'domain'  => $installation['softdomain'],
                        'url'     => $installation['softurl'],
                        'path'    => $installation['softpath'],
                        'version' => $installation['ver'],
                        'staging' => $installation['is_staging'],
                        'domain_id' => (string)$entity->id,
                    ];
                }
            }
        }
        return $collection;
    }

    public function installationCreate(array $data)
    {
        $appId = 26;
        if ($data['installationScript'])
        {
            $appId = $data['installationScript'];
            unset($data['installationScript']);
        }
        $softaculous = SoftaculousFactory::fromParamsAsUser($this->params);
        $site = (new Domain($this->params))->findDomain($data['softdomain']);
        if($site['id']){
            $softaculous->getProvider()->setCookieSoftdomId($site['id']);
        }
        return $softaculous->installationCreate($appId, $data);
    }

    public function installationDetail($id)
    {
        $softaculous = SoftaculousFactory::fromParamsAsUser($this->params);

        $domainId = Installation::ofHostingId($this->params['serviceid'])->ofRelationId($id)->value('domain_id');
        if( $domainId){
            $softaculous->getProvider()->setCookieSoftdomId( $domainId);
        }
        $response  = $softaculous->installationDetail($id);
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
        ];
        $colection = array_merge($colection, $response['userins']);
        return $colection;
    }

    public function installationDelete($installationId, array $data)
    {
        $post = ['noemail' => 1];
        if ($data['directoryDelete'] == "on")
        {
            $post['remove_dir']     = 1;
            $post['remove_datadir'] = 1;
        }
        if ($data['databaseDelete'] == "on")
        {
            $post['remove_db']     = 1;
            $post['remove_dbuser'] = 1;
        }
        $softaculous = SoftaculousFactory::fromParamsAsUser($this->params);
        $domainId = Installation::ofHostingId($this->params['serviceid'])->ofRelationId($installationId)->value('domain_id');
        if( $domainId){
            $softaculous->getProvider()->setCookieSoftdomId( $domainId);
        }
        return  $softaculous->installationDelete($installationId, $post);

    }

    public function installationUpdate($installationId, array $data)
    {
        $softaculous = SoftaculousFactory::fromParamsAsUser($this->params);
        $domainId = Installation::ofHostingId($this->params['serviceid'])->ofRelationId($installationId)->value('domain_id');
        if( $domainId){
            $softaculous->getProvider()->setCookieSoftdomId( $domainId);
        }
        return  $softaculous->installationUpdate($installationId, $data);
    }

    public function getBackups($installationId)
    {
        $collection = [];
        $domainId = Installation::ofHostingId($this->params['serviceid'])->ofRelationId($installationId)->value('domain_id');
        $softaculous = SoftaculousFactory::fromParamsAsUser($this->params);
        if( $domainId){
            $softaculous->getProvider()->setCookieSoftdomId( $domainId);
        }
        foreach ($softaculous->getBackups($installationId) as $apId => $instaltions)
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
     * @param array $data ['installationId'] - The Installation ID that you want to clone. It can be fetched from List Installed Script
     * @param array $data ['backupDirectory']
     * @param array $data ['backupDatabase']
     * @param array $data ['note']
     * @return array
     */
    public function backupCreate(array $data)
    {
        $domainId = Installation::ofHostingId($this->params['serviceid'])->ofRelationId($data['installationId'])->value('domain_id');
        $softaculous = SoftaculousFactory::fromParamsAsUser($this->params);
        if( $domainId){
            $softaculous->getProvider()->setCookieSoftdomId( $domainId);
        }
        $request  = [
            'backup_dir'     => $data['backupDirectory'],
            'backup_datadir' => $data['backupDataDir'],
            'backup_db'      => $data['backupDatabase'],
            'backup_note'=> $data['backup_note'],
        ];
        return $softaculous->backupCreate($data['installationId'], $request);
    }

    public function backupDownload($backupId, Installation $installation)
    {
        $softaculous = SoftaculousFactory::fromParamsAsUser($this->params);
        if( $installation->domain_id){
            $softaculous->getProvider()->setCookieSoftdomId( $installation->domain_id);
        }
        return  $softaculous->backupDownload($backupId);
    }

    public function backupRestore($backupId, Installation $installation)
    {
        $softaculous = SoftaculousFactory::fromParamsAsUser($this->params);
        if( $installation->domain_id){
            $softaculous->getProvider()->setCookieSoftdomId( $installation->domain_id);
        }
        return $softaculous->backupRestore($backupId);
    }

    public function backupDelete($backupId, Installation $installation)
    {
        $softaculous = SoftaculousFactory::fromParamsAsUser($this->params);
        if( $installation->domain_id){
            $softaculous->getProvider()->setCookieSoftdomId( $installation->domain_id);
        }
        return $softaculous->backupDelete($backupId);
    }

    /**
     *
     * @param array $data ['installationId'] - The Installation ID that you want to clone. It can be fetched from List Installed Script
     * @param array $data ['domain'] - This is the domain on which you wish to clone the installation
     * @param array $data ['directory'] - This is the sub-directory to clone the installation in. Leave it blank to clone in root of the domain
     * @param array $data ['db'] - This is the database name for the cloned installation. If the script does not require database you can leave this blank.
     * @return array
     */
    public function installationClone(array $data)
    {
        $instDomain = Installation::ofHostingId($this->params['serviceid'])
                              ->ofRelationId($data['installationId'])
                              ->value("domain");
        if($instDomain !=$data['softdomain']){
            $lang =  sl("lang");
            $lang->addReplacementConstant('domain', $instDomain);
            throw new \Exception($lang->abtr('You cannot clone from one domain to another, please provide the installation domain: :domain:'));
        }
        $request = [
            'softsubmit'    => 1,
            'softdomain'    => $data['softdomain'],
            'softdirectory' => $data['softdirectory'],
            'softdb'        => $data['softdb'],
        ];
        $softaculous = SoftaculousFactory::fromParamsAsUser($this->params);
        $domainId = Installation::ofHostingId($this->params['serviceid'])->ofRelationId($data['installationId'])->value('domain_id');
        if( $domainId){
            $softaculous->getProvider()->setCookieSoftdomId( $domainId);
        }
        return $softaculous->installationClone($data['installationId'], $request);
    }

    public function installationUpgrade($installationId)
    {
        $domainId = Installation::ofHostingId($this->params['serviceid'])->ofRelationId($installationId)->value('domain_id');
        $softaculous = SoftaculousFactory::fromParamsAsUser($this->params);
        if( $domainId){
            $softaculous->getProvider()->setCookieSoftdomId( $domainId);
        }
        return $softaculous->installationUpgrade($installationId);
    }

    /**
     *
     * @param string $installationId
     * @return string SSO URL
     */
    public function getSingleSignOnUrl($installationId)
    {
        $domainId = Installation::ofHostingId($this->params['serviceid'])->ofRelationId($installationId)->value('domain_id');
        $softaculous = SoftaculousFactory::fromParamsAsUser($this->params);
        if( $domainId){
            $softaculous->getProvider()->setCookieSoftdomId( $domainId);
        }
        $response = $softaculous->signOn($installationId);
        return $response['sign_on_url'];
    }

    public function cacheFlush(Installation $installation)
    {
        return WpCliFactory::fromParamsAndInstallation($this->params, $installation)->cache()->flush();
    }

    public function getPlugins(Installation $installation)
    {
        $collection = [];
        $response   = WpCliFactory::fromParamsAndInstallation($this->params, $installation)->plugin()->getList();
        foreach ($response as $plugin)
        {
            $row          = [
                "installationId" => $installation->id,
                "id"             => base64_encode(json_encode($plugin))
            ];
            $collection[] = array_merge($row, $plugin);
        }
        return $collection;
    }

    public function getThemes(Installation $installation)
    {
        $collection = [];
        $response   = WpCliFactory::fromParamsAndInstallation($this->params, $installation)->theme()->getList();
        foreach ($response as $theme)
        {
            $row          = [
                "installationId" => $installation->id,
                "id"             => base64_encode(json_encode($theme))
            ];
            $collection[] = array_merge($row, $theme);
        }
        return $collection;
    }

    public function pluginActivate(Installation $installation, $pluginName)
    {
        return WpCliFactory::fromParamsAndInstallation($this->params, $installation)->plugin()->activate($pluginName);
    }

    public function pluginInstall(Installation $installation, $pluginName)
    {
        return WpCliFactory::fromParamsAndInstallation($this->params, $installation)->plugin()->install($pluginName);
    }

    public function pluginDeactivate(Installation $installation, $pluginName)
    {
        return WpCliFactory::fromParamsAndInstallation($this->params, $installation)->plugin()->deactivate($pluginName);
    }

    public function pluginUpdate(Installation $installation, $pluginName)
    {
        return WpCliFactory::fromParamsAndInstallation($this->params, $installation)->plugin()->update($pluginName);
    }

    /**
     *
     * @param Installation $installation
     * @return WordPressConfigInterface
     */
    public function getConfig(Installation $installation)
    {
        return WpCliFactory::fromParamsAndInstallation($this->params, $installation)->config();
    }

    public function domainCreate(array $data)
    {
        return $this->domain($this->params)
            ->setAttributes([
                'domain'    => $data['newDomain'],
                'dir'       => $data['path'],
                'subdomain' => $data['subDomain'],
                'rootdomain' => $this->params['domain']
            ])->create();
    }

    public function domainInfo()
    {
        //do nothing
    }

    public function getAddonDomains()
    {
        $enteries = [];
        foreach (ApiClientFactory::fromParamsAsUser($this->params)->site()->getAll() as $k => $entery)
        {
            if ($this->params['domain'] == $entery->name)
            {
                continue;
            }
            $enteries[] = ['domain' => $entery->name];
        }
        return $enteries;
    }

    public function getChangeDomainFields(Installation $installation)
    {
        return [
             new Text('newDomain')
        ];
    }

    public function setDebugMode($debugMode)
    {
        $this->debugMode = $debugMode;
        $this->params['debug'] = $debugMode;
        return $this;
    }

    public function getInstallationScript()
    {
        return SoftaculousFactory::fromParamsAsUser($this->params)->getWordPressInstallationScript();
    }


    public function pluginSearch(Installation $installation, $name)
    {
        $collection = [];
        $response   = WpCliFactory::fromParamsAndInstallation($this->params, $installation)->plugin()->search($name);
        foreach ($response as $plugin)
        {
            $row          = [
                "installationId" => $installation->id,
                "id"             => base64_encode(json_encode($plugin))
            ];
            $collection[] = array_merge($row, $plugin);
        }
        return $collection;
    }

    /**
     *
     * @param Installation $installation
     * @return WordPressPluginInterface Description
     */
    public function getPlugin(Installation $installation)
    {
        return WpCliFactory::fromParamsAndInstallation($this->params, $installation)->plugin();
    }

    /**
     *
     * @param Installation $installation
     * @return ThemeInterface Description
     */
    public function getTheme(Installation $installation)
    {
        return WpCliFactory::fromParamsAndInstallation($this->params, $installation)->theme();
    }

    public function getWpCli(Installation $installation)
    {
        return WpCliFactory::fromParamsAndInstallation($this->params, $installation);

    }

    /**
     *
     * @param Installation $installation
     * @return InstallationInterface
     */
    public function installation(Installation $installation)
    {
        $softaculous = SoftaculousFactory::fromParamsAsUser($this->params);
        if($installation->domain_id){
            $softaculous->getProvider()->setCookieSoftdomId($installation->domain_id);
        }
        return (new \ModulesGarden\WordpressManager\App\Modules\Softaculous\Installation($installation))->setSoftaCulous( $softaculous);
    }

    public function import(array $post)
    {
        $post['remote_submit'] = 1;
        $soft               = $post['soft'];
        $softaculous = SoftaculousFactory::fromParamsAsUser($this->params);
        $site = (new Domain($this->params))->findDomain($post['softdomain']);
        if($site['id']){
            $softaculous->getProvider()->setCookieSoftdomId($site['id']);
        }
        unset($post['soft']);
        return $softaculous->setGet(['act' => 'import ', 'soft' => $soft, 'api' => 'json'])
                                                                 ->setPost($post)
                                                                 ->sendRequest();
    }

    /**
     * @return Ssl
     */
    public function ssl()
    {
        return new Ssl( $this->params);
    }

    /**
     * @return \ModulesGarden\WordpressManager\App\Modules\Cpanel\Domain|\ModulesGarden\WordpressManager\App\Modules\DirectAdmin\Domain|Domain
     */
    public function domain()
    {
        if(is_null($this->domain)){
            $this->domain = new Domain($this->params);
        }
        return $this->domain;
    }

    public function isSupportChangeDomain()
    {
        return false;
    }

    /**
     * @return \ModulesGarden\WordpressManager\App\Modules\Cpanel\Reseller|\ModulesGarden\WordpressManager\App\Modules\DirectAdmin\Reseller
     */
    public function reseller()
    {
        return null;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function onInstallationCreated(Installation $installation){
        //nothigh to do
    }

}
