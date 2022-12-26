<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 12, 2017)
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
use ModulesGarden\WordpressManager\App\Modules\Cpanel\Uapi;
use ModulesGarden\WordpressManager\App\Helper\UtilityHelper;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use \ModulesGarden\WordpressManager\App\Helper\LangException;
use \ModulesGarden\WordpressManager\Core\ServiceLocator;

/**
 * Description of Cpanel
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class Cpanel implements WordPressModuleInterface
{
    protected $params;
    protected $api;
    protected $uapi;
    protected $cpanel;
    protected $debugMode;
    protected $domainService;
    private $themeBase;

    public function setParams(array $params)
    {
        if (!$params['username']) {
            throw (new LangException("API: Username is empty"))->translate();
        }
        if (!$params['password'] && !$params['serveraccesshash']) {
            throw (new LangException("API: Username's password is empty"))->translate();
        }
        if (!$params['serverip'] && !$params['serverhostname']) {
            throw (new LangException("API: server's ip or host is empty"))->translate();
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
     * @return  Softaculous\Softaculous
     */
    public function api()
    {
        if (!is_null($this->api)) {
            return $this->api;
        }

        $hostUrl = $this->uapi()->getSessionUrl() . '/frontend/' . $this->getThemeBase() . '/softaculous/index.live.php';
        $provider = new Softaculous\CpanelProvider($hostUrl);
        if ($this->debugMode) {
            $provider->setLoger(new \ModulesGarden\WordpressManager\App\Helper\Loger('WpSoftaculous'));
        }
        $provider->login();
        $provider->setHeader(['uapi' => $this->uapi()]);

        $this->api = new Softaculous\Softaculous($provider);
        return $this->api;
    }

    public function softaculous()
    {
        return $this->api();
    }

    public function setDebugMode($debugMode)
    {
        $this->debugMode = $debugMode;
        return $this;
    }

    /**
     *
     * @return Uapi
     */
    public function uapi()
    {
        if (!is_null($this->uapi)) {
            return $this->uapi;
        }
        $this->uapi = new Uapi();
        if ($this->debugMode) {
            $this->uapi->setLoger(new \ModulesGarden\WordpressManager\App\Helper\Loger('WpUapi'));
        }
        $this->uapi->setLogin($this->params);
        if ($this->getUsername()) {
            $this->uapi->setQueryuser($this->getUsername());
        }
        $this->uapi->createSession();
        return $this->uapi;
    }

    /**
     *
     * @return Cpanel\CPanel
     */
    public function cpanel()
    {
        if (!is_null($this->cpanel)) {
            return $this->cpanel;
        }
        $this->cpanel = new Cpanel\CPanel();
        if ($this->debugMode) {
            $this->cpanel->setLoger(new \ModulesGarden\WordpressManager\App\Helper\Loger('cpanel', [$this->params['serverpassword']]));
        }
        $this->cpanel->setLogin($this->params);
        if ($this->getUsername()) {
            $this->cpanel->setQueryuser($this->getUsername());
        }
        return $this->cpanel;
    }

    public function getInstallations($appIds = [])
    {
        if (empty($appIds)) {
            $app = $this->api()->getWordPressInstallationScript();
            $appIds[] = $app['id'];
        }
        unset($this->api);
        $data = $this->api()->getInstallations();
        $collection = [];
        foreach ($data['installations'] as $apId => $Installations) {
            foreach ($Installations as $id => $Installation) {
                $softId = explode("_", $id);
                $softId = $softId[0];
                if (!in_array($softId, $appIds)) {
                    continue;
                }
                $collection[] = [
                    'id' => $id,
                    'domain' => $Installation['softdomain'],
                    'url' => $Installation['softurl'],
                    'path' => $Installation['softpath'],
                    'version' => $Installation['ver'],
                    'staging' => $Installation['is_staging'],
                ];
            }
        }
        return $collection;
    }

    public function installationCreate(array $data)
    {
        if ($data['installationScript']) {
            $app['id'] = $data['installationScript'];
            unset($data['installationScript']);
        } else {
            $app = $this->api()->getWordPressInstallationScript();
        }
        return $this->api()->installationCreate($app['id'], $data);
    }

    public function installationUpdate($id, array $data)
    {
        return $this->api()->installationUpdate($id, $data);
    }

    public function installationDelete($id, array $data)
    {
        $post = ['noemail' => 1];
        if ($data['directoryDelete'] == "on") {
            $post['remove_dir'] = 1;
            $post['remove_datadir'] = 1;
        }
        if ($data['databaseDelete'] == "on") {
            $post['remove_db'] = 1;
            $post['remove_dbuser'] = 1;
        }
        return $this->api()->installationDelete($id, $post);
    }

    public function installationDetail($installationId)
    {
        $response = $this->api()->installationDetail($installationId);
        $colection = [
            'domain' => $response['userins']['softdomain'],
            'siteName' => $response['userins']['site_name'],
            'version' => $response['userins']['ver'],
            'path' => $response['userins']['softpath'],
            'url' => $response['userins']['softurl'],
            'db' => $response['userins']['softdb'],
            'dbHost' => $response['userins']['softdbhost'],
            'dbUser' => $response['userins']['softdbuser'],
            'dbPass' => $response['userins']['softdbpass'],
        ];
        $colection = array_merge($colection, $response['userins']);
        return $colection;
    }

    public function getBackups($installationId)
    {
        $collection = [];
        foreach ($this->api()->getBackups($installationId) as $apId => $instaltions) {
            foreach ($instaltions as $instId => $backups) {
                foreach ($backups as $k => $backup) {
                    if ($instId == $installationId) {
                        $collection[] =
                            [
                                'id' => $backup['name'],
                                'installationId' => $instId,
                                'name' => $backup['name'],
                                'note' => $backup['backup_note'],
                                'path' => $backup['path'],
                                'size' => UtilityHelper::formatBytes($backup['size']),
                                'bytes' => $backup['size'],
                                'version' => $backup['ver'],
                                'timestamp' => $backup['btime'],
                                'date' => date("Y-m-d H:i:s", $backup['btime'])
                            ];
                    }
                }
            }
        }

        return $collection;
    }

    /**
     *
     * @param array $data ['installationId'] - The Installation ID that you want to clone. It can be fetched from List Installed Script
     * @param array $data ['backupDirectory']
     * @param array $data ['backupDataDir']
     * @param array $data ['backupDatabase']
     * @param array $data ['note']
     * @return array
     */
    public function backupCreate(array $data)
    {
        $request = [
            'backup_dir' => $data['backupDirectory'],
            'backup_datadir' => $data['backupDataDir'],
            'backup_db' => $data['backupDatabase'],
            'backup_note' => $data['backup_note'],
        ];
        $response = $this->api()->backupCreate($data['installationId'], $request);
    }

    public function backupRestore($backupId, Installation $installation)
    {
        return $this->api()->backupRestore($backupId);
    }

    public function backupDelete($fileName, Installation $installation)
    {
        return $this->api()->backupDelete($fileName);
    }

    /**
     *
     * @param array $data ['installationId'] The Installation ID that you want to clone. It can be fetched from List Installed Script
     * @param array $data ['domain'] This is the domain on which you wish to clone the installation
     * @param array $data ['directory'] This is the sub-directory to clone the installation in. Leave it blank to clone in root of the domain
     * @param array $data ['db']
     * @return array
     */
    public function installationClone(array $data)
    {
        $request = [
            'softsubmit' => 1,
            'softdomain' => $data['softdomain'],
            'softdirectory' => $data['softdirectory'],
            'softdb' => $data['softdb'],
        ];

        return $this->api()->installationClone($data['installationId'], $request);
    }

    public function installationUpgrade($installationId)
    {
        return $this->api()->installationUpgrade($installationId);
    }

    public function getSingleSignOnUrl($installationId)
    {
        $response = $this->api()->signOn($installationId);
        return $response['sign_on_url'];
    }

    public function cacheFlush(Installation $installation)
    {
        $response = $this->uapi()->wp($installation->path)->cache()->flush();
        return $response;
    }

    public function backupDownload($backupId, Installation $installation)
    {
        return $this->api()->backupDownload($backupId);
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
        $response = $this->uapi()->wp($installation->path)->plugin()->getList();
        foreach ($response['data'] as $plugin) {
            if ($plugin['status'] == 'dropin') {
                continue;
            }
            $row = [
                "installationId" => $installation->id,
                "id" => base64_encode(json_encode($plugin))
            ];
            $collection[] = array_merge($row, $plugin);
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
        $response = $this->uapi()->wp($installation->path)->theme()->getList();
        foreach ($response as $theme) {
            if ($theme['status'] == 'dropin') {
                continue;
            }
            $row = [
                "installationId" => $installation->id,
                "id" => base64_encode(json_encode($theme))
            ];
            $collection[] = array_merge($row, $theme);
        }
        return $collection;
    }

    public function pluginSearch(Installation $installation, $name)
    {
        $collection = [];
        $response = $this->uapi()->wp($installation->path)->plugin()->search($name);
        foreach ($response['data'] as $plugin) {
            $row = [
                "installationId" => $installation->id,
                "id" => base64_encode(json_encode($plugin))
            ];
            $collection[] = array_merge($row, $plugin);
        }
        return $collection;
    }

    public function pluginActivate(Installation $installation, $pluginName)
    {
        return $this->uapi()->wp($installation->path)->plugin()->activate($pluginName);
    }

    public function pluginDeactivate(Installation $installation, $pluginName)
    {
        return $this->uapi()->wp($installation->path)->plugin()->deactivate($pluginName);
    }

    public function pluginUpdate(Installation $installation, $pluginName)
    {
        return $this->uapi()->wp($installation->path)->plugin()->update($pluginName);
    }

    public function pluginUpdateAll(Installation $installation)
    {
        return $this->uapi()->wp($installation->path)->plugin()->updateAll();
    }

    public function getConfig(Installation $installation)
    {
        return $this->uapi()->wp($installation->path)->config();
    }

    public function domainCreate(array $data)
    {

        if (preg_match("/{$this->params['domain']}/", $data['newDomain'])) {
            $request = [
                'domain' => $data['newDomain'],
                'rootdomain' => $this->params['domain'],
                'dir' => $data['path'],
            ];
            return $this->cpanel()->api2->SubDomain->addsubdomain($request, 'sobj');
        }
        $request = [
            'dir' => $data['path'],
            'newdomain' => $data['newDomain'],
            'subdomain' => $data['subDomain'],
            'pass' => $data['password'],
        ];
        return $this->cpanel()->api2->AddonDomain->addaddondomain($request, 'sobj');
    }

    public function domainInfo()
    {
        $this->uapi()->exec('domains_data', "/execute/DomainInfo/");
        $response = json_decode($this->uapi()->getLastResponse(), true);
        return $response['data'];
    }

    public function getAddonDomains()
    {
        $result = $this->cpanel()->api2->AddonDomain->listaddondomains([], 'array');
        $addonDomains = (array)$result['cpanelresult']['data'];
        $result = $this->cpanel()->api2->SubDomain->listsubdomains([], 'array');
        return array_merge($addonDomains, (array)$result['cpanelresult']['data']);
    }

    public function getChangeDomainFields(Installation $installation)
    {
        $fields = [];
        $options = ["0" => ServiceLocator::call('lang')->absoluteT('Enter new domain')];
        $options [$installation->hosting_id] = $installation->hosting->domain;
        foreach ($this->getAddonDomains() as $addonDomain) {
            $options[$addonDomain['domain']] = $addonDomain['domain'];
        }
        $field = new Fields\Select('domain');
        $field->setAvailableValues($options);
        $fields[] = $field;
        $fields[] = new Fields\Text('newDomain');
        $fields[] = new Fields\Password('password');
        return $fields;
    }

    public function pluginInstall(Installation $installation, $pluginName)
    {
        return $this->uapi()->wp($installation->path)->plugin()->install($pluginName);
    }

    public function getInstallationScript()
    {
        return $this->api()->getWordPressInstallationScript();
    }

    /**
     *
     * @param Installation $installation
     * @return \ModulesGarden\WordpressManager\App\Interfaces\WordPressPluginInterface
     */
    public function getPlugin(Installation $installation)
    {
        return $this->uapi()->wp($installation->path)->plugin();
    }

    public function getInstallationScripts()
    {
        return $this->api()->getWordPressInstallationScripts();
    }

    public function getTheme(Installation $installation)
    {

        return $this->uapi()->wp($installation->path)->theme();
    }

    public function getWpCli(Installation $installation)
    {
        return $this->uapi()->wp($installation->path);
    }

    public function installation(Installation $installation)
    {
        return (new Softaculous\Installation($installation))->setSoftaCulous($this->api());
    }

    public function import(array $post)
    {
        $post['remote_submit'] = 1;
        $soft = $post['soft'];
        unset($post['soft']);
        return $this->api()->setGet(['act' => 'import ', 'soft' => $soft, 'api' => 'json'])
            ->setPost($post)
            ->sendRequest();
    }

    public function ssl()
    {
        return new Cpanel\Ssl($this->cpanel());
    }

    public function domain()
    {
        if ($this->domainService) {
            return $this->domainService;
        }
        $this->domainService = new Cpanel\Domain($this->cpanel());
        $this->domainService->setUapi($this->uapi());
        return $this->domainService;
    }

    public function isSupportChangeDomain()
    {
        return false;
    }

    public function reseller()
    {
        return new Cpanel\Reseller($this->uapi());
    }

    public function setUsername($username)
    {
        $this->username = $username;
        unset($this->api, $this->uapi);

    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getThemeBase()
    {
        if (!is_null($this->themeBase)) {
            return $this->themeBase;
        }
        $result = $this->uapi()->exec('get_user_information', '/execute/Variables/', []);
        if(isset( $result['data']['theme']))
        {
            return $this->themeBase =  $result['data']['theme'];
        }
        $response = $this->uapi()->exec('get_theme_base', '/execute/Themes/');
        if ($response['data']) {
            $this->themeBase = $response['data'];
        } else if ($this->params['configoption13']) {
            $this->themeBase = $this->params['configoption13'];
        } else {
            $this->themeBase = 'paper_lantern';
        }
        return $this->themeBase;
    }


}
