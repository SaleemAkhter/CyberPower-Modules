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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Providers;

use ModulesGarden\WordpressManager\App\Events\InstallationCreatedEvent;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use ModulesGarden\WordpressManager\App\Interfaces\WordPressModuleInterface;
use ModulesGarden\WordpressManager\App\Jobs\MultisiteDisableJob;
use ModulesGarden\WordpressManager\App\Jobs\SslEnableJob;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Models\PluginPackage;
use ModulesGarden\WordpressManager\App\Models\WebsiteDetails;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\Modules\Plesk;
use ModulesGarden\WordpressManager\App\Modules\Plesk\SoftaculousFactory;
use ModulesGarden\WordpressManager\App\Repositories\HostingRepository;
use ModulesGarden\WordpressManager\App\Repositories\InstallationRepository;
use ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\Core\Models\Whmcs\Client;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;


use function ModulesGarden\WordpressManager\Core\Helper\queue;

use function ModulesGarden\WordpressManager\Core\Helper\sl;

use ModulesGarden\WordpressManager\Core\Helper\BuildUrl;

/**
 * Description of InstallationProvider
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class InstallationProvider extends BaseDataProvider implements ClientArea
{

    use BaseClientController;

    /**
     *
     * @var WordPressModuleInterface
     */
    private $module;

    public function create()
    {
        $request = Helper\sl('request');
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        try
        {
            $form    = $request->get('formData');

            if((!isset($form['hostingId']) || empty($form['hostingId'])) ){
                $this->formData['hostingId']=$form['hostingId']=(isset($_GET['hostingId']) )?$_GET['hostingId']:array_key_first($_SESSION['resellerloginas']);

            }
            $form['username']=Helper\loggedinUsername();
            $hosting = Hosting::findOrFail($form['hostingId']);
            /* @var $hosting Hosting */
            if ($hosting->userid != $request->getSession('uid'))
            {
                throw new \Exception("Access Denied");
            }
            //Installation Script  validation
            if (!$form['installationScript'] && $hosting->productSettings->getSettings()['installationScripts'])
            {
                return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Installation Script is required')->setStatusError();
            }
            $module = Wp::subModule($hosting);
            if ($form['username'])
            {
                $module->setUsername($form['username']);
            }
            unset($form['username']);
            $form['eu_auto_upgrade']      = $form['eu_auto_upgrade'];
            $form['auto_upgrade_themes']  = $form['auto_upgrade_themes'] == "on" ? "1" : "";
            $form['auto_upgrade_plugins'] = $form['auto_upgrade_plugins'] == "on" ? "1" : "";
            unset($form['hostingId'], $form['pluginPackages']);

            $module->domain()->setAttributes(['domain' => $this->formData['softdomain'], 'rootdomain' => $hosting->domain]);
            if ($form['softdomain'] && $form['softdomain'] != $hosting->domain && !$module->domain()->exist())
            {
                $ex        = explode(".", $this->formData['softdomain']);
                $subdomain = $ex[0];
                $module->domain()->setAttributes([
                    'domain'     => $form['softdomain'],
                    'dir'        => sprintf("/%s", $form['softdomain']),
                    'subdomain'  => $subdomain,
                    'rootdomain' => $hosting->domain
                ])->create();
            }
            if ($form['multisite'] == 'off')
            {
                unset($form['multisite']);
            }


            $form['softsubmit']="Install";
            if(!isset($form['softbranch']) || empty($form['softbranch'])){
                $form['softbranch']=26;
            }


            if ($form['disable_wp_cron'] == 'on')
            {
                $form['disable_wp_cron'] = "off";
            }else{
                unset($form['disable_wp_cron']);
            }

            $response = $module->installationCreate((array)$form);

            $model    = $this->synchronize($module, $hosting);
            if ($model)
            {
                $model->site_name=$form['site_name'];
                $model->additional_data=$response;
                $model->save();
                //SSL on
                if (in_array($this->formData['softproto'], ['3', '4']))
                {
                    queue(SslEnableJob::class, ['installationId' => $model->id]);
                }
                if ($form['multisite'] == 'off')
                {
                    $config = $module->getConfig($model);
                    $config->set('MULTISITE', '0', 'constant');
                    $config->set('WP_ALLOW_MULTISITE', '0', 'constant');
                }
                if (!$form['eu_auto_upgrade'] || !$form['auto_upgrade_themes'] || !$form['auto_upgrade_plugins'])
                {
                    $module->installationUpdate($model->relation_id, [
                        'eu_auto_upgrade'      => $form['eu_auto_upgrade'],
                        'auto_upgrade_themes'  => $form['auto_upgrade_themes'],
                        'auto_upgrade_plugins' => $form['auto_upgrade_plugins'],
                        'noemail'              => 1
                    ]);
                }
                Helper\infoLog(sprintf('Installation ID #%s has been created, Client ID #%s', $model->id, $request->getSession('uid')));
                if ($this->formData['pluginPackages'])
                {
                    $this->installPluginPackages($model, $this->formData['pluginPackages']);
                }
                if ($this->formData['customPlugins'])
                {
                    $this->installCustomPlugins($model, $this->formData['customPlugins']);
                }
                $sitesetting=$response['__settings'];
                $sitedata=[
                    'site_url'=>$sitesetting['softurl'],
                    'admin_url'=>$sitesetting['softurl']."/".$sitesetting['adminurl'],
                    'autologinurl'=> BuildUrl::getUrl('home', 'controlPanel',['id'=>$hosting->id,'wpid'=>$model->id]),

                ];
                return (new ResponseTemplates\HtmlDataJsonResponse())->setData($sitedata)->setMessageAndTranslate('Installation has been created successfully');
            }
            $arguments = [
                'domain'    => $this->formData['softdomain'],
                'softurl'   => $response['__settings']['softurl'],
                'softpath'  => $response['__settings']['softpath'],
                'hostingId' => $this->formData['hostingId'],
            ];
            if (!$model && in_array($this->formData['softproto'], ['3', '4']))
            {  //SSL on
                queue(SslEnableJob::class, $arguments);
            }
            //multisite off
            if (!$model && $form['multisite'] == 'off')
            {
                queue(MultisiteDisableJob::class, $arguments);
            }

            Helper\infoLog(sprintf('Installation creation in progress, Client ID #%s', $request->getSession('uid')));
            return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Installation creation in progress');;
        }
        catch (\Exception $ex)
        {
            $msg = sprintf('Installation create failed: %s, Client ID #%s, Hosting ID #%s', $ex->getMessage(), $request->getSession('uid'), $this->formData['hostingId']);
            Helper\errorLog($msg, $request->get('formData'), [$ex->getTraceAsString()]);

            throw $ex;
        }
    }

    private function domainCreate()
    {
        $this->loadRequestObj();
        $this->setHostingId($this->formData['hostingId'])
        ->setUserId($this->request->getSession('uid'));
        $this->getHosting();
        if ($this->formData['username'])
        {
            $this->subModule()->setUsername($this->formData['username']);
        }
        foreach ($this->subModule()->getAddonDomains() as $d)
        {
            if (($d['subdomain'] && $d['subdomain'] == $this->formData['softdomain']) || ($d['domain'] && $d['domain'] == trim($this->formData['softdomain'])))
            {
                return true;
            }
        }
        $ex        = explode(".", $this->formData['softdomain']);
        $subdomain = $ex[0];
        $request   = [
            "newDomain" => $this->formData['softdomain'],
            "subDomain" => $subdomain,
            "path"      => sprintf("/%s", $this->formData['softdomain'])
        ];
        if ($this->formData['softdirectory'])
        {
            $request['path'] .= sprintf("/%s", $this->formData['softdirectory']);
        }
        $this->subModule()->domainCreate($request);
        Helper\infoLog(sprintf('Domain has been created, Client ID #%s, Hosting ID #%s', $this->request->getSession('uid'), $this->formData['hostingId']));
    }
    public function testinstallPluginPackages(Installation $instalation, $pluginPackageIds)
    {
        $this->loadRequestObj();
        $this->reset();
        $this->setInstallation($instalation)
        ->setUserId($this->request->getSession('uid'));
        $this->setHostingId(7);
        $form['username']=Helper\loggedinUsername();
            $hosting = Hosting::findOrFail(7);
        $names = [];
        $module = Wp::subModule($hosting);
            if ($form['username'])
            {
                $module->setUsername($form['username']);
            }
        foreach ($pluginPackageIds as $packageId)
        {
            unset($this->pluginPackage);
            $this->setPluginPackageId($packageId);
            $names[] = sprintf("#%s %s", $packageId, $this->getPluginPackage()->name);

            foreach ($this->getPluginPackage()->items as $item)
            {

                /* @var $item main\App\Models\PluginPackageItem */
                $response=$module->pluginInstall($this->getInstallation(), $item->slug);
            }
        }
        Helper\infoLog(sprintf("Plugin Packages %s have been  installed. Installation ID #%s, Client ID #%s, Hosting ID #%s", implode(", ", $names), $this->getInstallation()->id, $this->getInstallation()->user_id, $this->getInstallation()->hosting_id));
    }

    private function installCustomPlugins(Installation $instalation, $customPluginIds)
    {
        $this->loadRequestObj();
        $this->reset();
        $this->setInstallation($instalation)
        ->setUserId($this->request->getSession('uid'));
        $this->setHostingId($this->formData['hostingId']);

        $installationresults=$names = [];
        $this->subModule()->setUsername($installation->username);
        foreach ($customPluginIds as $pluginId)
        {
            if($pluginId=="off"){
                continue;
            }
            unset($this->customPlugin);
            $this->setCustomPluginId($pluginId);
            $names[] = sprintf("#%s %s", $pluginId, $this->getCustomPlugin()->name);
            $plugin=$this->getCustomPlugin();

            $installationresults[]=  $this->subModule()->setUsername($installation->username)->pluginInstall($this->getInstallation(), $plugin->url);

        }
        logActivity("Installations ",json_encode($installationresults));
        Helper\infoLog(sprintf("Plugin Packages %s have been  installed. Installation ID #%s, Client ID #%s, Hosting ID #%s", implode(", ", $names), $this->getInstallation()->id, $this->getInstallation()->user_id, $this->getInstallation()->hosting_id));
    }
    private function installPluginPackages(Installation $instalation, $pluginPackageIds)
    {
        $this->loadRequestObj();
        $this->reset();
        $this->setInstallation($instalation)
        ->setUserId($this->request->getSession('uid'));
        $this->setHostingId($this->formData['hostingId']);

        $this->subModule()->setUsername($installation->username);
        $installationresults=$names = [];

        foreach ($pluginPackageIds as $packageId)
        {
            if($packageId=="off"){
                continue;
            }
            unset($this->pluginPackage);
            $this->setPluginPackageId($packageId);
            $names[] = sprintf("#%s %s", $packageId, $this->getPluginPackage()->name);
            foreach ($this->getPluginPackage()->items as $item)
            {
                /* @var $item main\App\Models\PluginPackageItem */
              $installationresults[]=  $this->subModule()->setUsername($installation->username)->pluginInstall($this->getInstallation(), $item->slug);
            }
        }
        Helper\infoLog(sprintf("Plugin Packages %s have been  installed. Installation ID #%s, Client ID #%s, Hosting ID #%s", implode(", ", $names), $this->getInstallation()->id, $this->getInstallation()->user_id, $this->getInstallation()->hosting_id));
    }

    /**
     *
     * @param WordPressModuleInterface $module
     * @param Hosting $hosting
     * @return type
     */
    private function synchronize(WordPressModuleInterface $module, $hosting, $timeOut = 15)
    {
        $installationScripts = array_keys((array)$hosting->productSettings->getSettings()['installationScripts']);
        for ($i = 0; $i <= $timeOut; $i++)
        {
            foreach ($module->getInstallations($installationScripts) as $installation)
            {
                if ($installation['staging'])
                {
                    continue;
                }
                $repository = new InstallationRepository;
                $model      = $repository->forHostingAndRelation($hosting, $installation['id']);
                //for plesk
                if (!$model->id && Installation::ofRelationId($model->relation_id)->ofUserId($model->user_id)->count())
                {
                    continue;
                }
                if ($model->id)
                {
                    continue;
                }
                $model->domain   = $installation['domain'];
                $model->url      = $installation['url'];
                $model->path     = $installation['path'];
                $model->version  = $installation['version'];
                $model->username = $module->getUsername();
                if ($installation['domain_id'])
                {
                    $model->domain_id = $installation['domain_id'];
                }
                $model->save();
                $this->createWebsiteDetails();
                Helper\fire(new InstallationCreatedEvent($model));
                return $model;
            }
            sleep(1);
        }
    }

    public function read()
    {
        $this->data         = $this->formData;

        $this->data['wpid'] = $this->request->query->get('wpid');
        $installation = Installation::where('id', $this->getRequestValue('wpid'))
        ->where('user_id', $this->request->getSession('uid'))
        ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if ($installation->username)
        {
            $module->setUsername($installation->username);
        }
        $details = $module->installationDetail($installation->relation_id);
        $this->data=$details;
        if ($this->actionElementId)
        {
            $this->data['installation_id'] = $this->actionElementId;
        }

        if ($this->getRequestValue('wpid') && !$this->data['installation_id'])
        {
            $this->data['installation_id'] = $this->getRequestValue('wpid');
        }
        $this->data['softdirectory']=$details['path'];
        $this->data['eu_auto_upgrade']            = $details['userins']['eu_auto_upgrade'];
        $this->availableValues['eu_auto_upgrade'] = [
            0 => Helper\sl("lang")->abtr("Do not auto upgrade"),
            1 => Helper\sl("lang")->abtr("Upgrade to any latest version available (Major as well as Minor)"),
            2 => Helper\sl("lang")->abtr("Upgrade to Minor versions only"),
        ];
        $this->data['disable_notify_update']  = $details['userins']['disable_notify_update'] ? "on" : "off";
        $this->data['auto_upgrade_plugins'] = $details['userins']['auto_upgrade_plugins'] ? "on" : "off";
        $this->data['auto_upgrade_themes']  = $details['userins']['auto_upgrade_themes'] ? "on" : "off";
        $this->data['auto_backup']  = isset($details['userins']['auto_backup']) ? $details['userins']['auto_backup'] : "";
        $this->data['auto_backup_rotation']  = isset($details['userins']['auto_backup_rotation']) ? $details['userins']['auto_backup_rotation'] : 4;
        $this->data['backup_location']  = isset($details['userins']['backup_location']) ? $details['userins']['backup_location'] : "";
        $this->data['autobackup_cron_min']  = $this->data['autobackup_cron_hour']  = $this->data['autobackup_cron_day']  = $this->data['autobackup_cron_month']  = $this->data['autobackup_cron_weekday']  = "";

        $this->data['auto_backup_crontime']  = isset($details['userins']['auto_backup_crontime']) ? $details['userins']['auto_backup_crontime'] : "";





        //admin email
        $client                    = Client::find($this->request->getSession('uid'));
        $this->data['admin_email'] = $client->email;

    }
    public function updateSiteInfo(){

        $request = Helper\sl('request');
        /* @var  $installation  Installation */
        $wpid         = $request->get('wpid') ?? $this->formData['wpid'];
        $installation = Installation::where('id', $wpid)
        ->where('user_id', $request->getSession('uid'))
        ->firstOrFail();

        $module       = Wp::subModule($installation->hosting);
        if ($installation->username)
        {
            $module->setUsername($installation->username);
        }

        $post                         = [];
        $post['softurl']      = $this->formData['softurl'];
        $post['site_name']  = $this->formData['site_name'];
        $post['insid']=$installation->relation_id;
        $post['save_info']=1;

        $response=$module->installationUpdateSiteDetails($installation->relation_id, (array)$post);
        if(isset($response['done']) && $response['done']==1){
            $installation->site_name= $post['site_name'] ;
            $installation->url= $post['softurl'] ;
            $installation->save();
        }
        Helper\infoLog(sprintf('Installation has been updated #%s, Client ID #%s', $installation->id, $request->getSession('uid')));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Installation has been updated successfully')->addData('data',['insid'=>$installation->id,'url'=>$this->formData['softurl'],'site_name'=>$this->formData['site_name']]);
    }
    public function autodetect(){

        $request = Helper\sl('request');
        /* @var  $installation  Installation */
        $hostingid         = $request->get('id') ?? $this->formData['id'];
        $hosting= Hosting::where('id',$hostingid)->first();
        $username=Helper\loggedinUsername();
        $module       = Wp::subModule($hosting);
        $module->setUsername($username);

        $post                         = [];
        $post['csrf_token']='undefined';

        $response= $module->autodetectWp( (array)$post);
        if(!empty($response['list'])){
            // Helper\infoLog("Congratulations, the installations listed below have been successfully imported into Softaculous. You can now manage the installations via Softaculous.");
            $response['message']=Helper\sl("lang")->abtr("synccompletedsuccessfully");
            $response['status']='success';
            $response['list'][26]=array_values($response['list'][26]);

        }else{
            $response['message']=Helper\sl("lang")->abtr("synccompletedempty");
            $response['status']='error';
        }
        return $response;


    }

    public function updateSiteConfig(){

        $request = Helper\sl('request');
        /* @var  $installation  Installation */
        $wpid         = $request->get('wpid') ?? $this->formData['wpid'];
        $installation = Installation::where('id', $wpid)
        ->where('user_id', $request->getSession('uid'))
        ->firstOrFail();

        $module       = Wp::subModule($installation->hosting);
        if ($installation->username)
        {
            $module->setUsername($installation->username);
        }

        $post                         = [];
        $post['insid']=$installation->relation_id;
        $post[$this->formData['field']]=$this->formData['value'];
        $post['save']=1;
        $response=$module->installationUpdateSiteDetails($installation->relation_id, (array)$post);
        unset($_SESSION['installationDetails']['expire'][$installation->relation_id]);
        // if(isset($response['done']) && $response['done']==1){
        //     $installation->site_name= $post['site_name'] ;
        //     $installation->url= $post['softurl'] ;
        //     $installation->save();
        // }
        Helper\infoLog(sprintf('Installation has been updated #%s, Client ID #%s', $installation->id, $request->getSession('uid')));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Installation has been updated successfully');
    }
    public function updateOld()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request = Helper\sl('request');
        /* @var  $installation  Installation */
        $wpid         = $request->get('wpid') ?? $this->formData['wpid'];
        $installation = Installation::where('id', $wpid)
        ->where('user_id', $request->getSession('uid'))
        ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if ($installation->username)
        {
            $module->setUsername($installation->username);
        }
        $post                         = [];
        $post['eu_auto_upgrade']      = $this->formData['eu_auto_upgrade'];
        $post['auto_upgrade_themes']  = $this->formData['auto_upgrade_themes'] == "on" ? "1" : "";
        $post['auto_upgrade_plugins'] = $this->formData['auto_upgrade_plugins'] == "on" ? "1" : "";
        $post['noemail']              = 1;
        $module->installationUpdateOld($installation->relation_id, (array)$post);
        Helper\infoLog(sprintf('Installation has been updated #%s, Client ID #%s', $installation->id, $request->getSession('uid')));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Installation has been updated successfully');
    }
    public function update()
    {
        logActivity("IASDH9");
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request = Helper\sl('request');
        /* @var  $installation  Installation */
        $wpid         = $request->get('wpid') ?? $this->formData['wpid'];
        $installation = Installation::where('id', $wpid)
        ->where('user_id', $request->getSession('uid'))
        ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if ($installation->username)
        {
            $module->setUsername($installation->username);
        }
        $post                         = [];
        $post['eu_auto_upgrade']      = $this->formData['eu_auto_upgrade'];
        $post['auto_upgrade_themes']  = $this->formData['auto_upgrade_themes'] == "on" ? "1" : "";
        $post['auto_upgrade_plugins'] = $this->formData['auto_upgrade_plugins'] == "on" ? "1" : "";
        $post['noemail']              = $this->formData['disable_notify_update'] == "on" ? "1" : "";
        $post['disable_notify_update']= $this->formData['disable_notify_update'] == "on" ? "1" : "";
        $post['edit_dir']=$this->formData['softdirectory'];
        $post['edit_url']      = $this->formData['url'];
        $post['edit_dbname']      = $this->formData['db'];
        $post['edit_dbuser']      = $this->formData['dbUser'];
        $post['edit_dbpass']      = $this->formData['dbPass'];
        $post['edit_dbhost']      = $this->formData['dbHost'];
        $post['auto_upgrade_plugins']      = $this->formData['auto_upgrade_plugins'];
        $post['auto_upgrade_themes']      = $this->formData['auto_upgrade_themes'];
        $post['upgradeplugins']      = $this->formData['auto_upgrade_plugins'];
        $post['upgradetheme']      = $this->formData['auto_upgrade_themes'];
        $post['eu_auto_upgrade']      = $this->formData['eu_auto_upgrade'];
        $post['backup_location']      = $this->formData['backup_location'];
        $post['auto_backup']      = $this->formData['auto_backup'];
        $post['autobackup_cron_min']      = $this->formData['autobackup_cron_min'];
        $post['autobackup_cron_hour']      = $this->formData['autobackup_cron_hour'];
        $post['autobackup_cron_day']      = $this->formData['autobackup_cron_day'];
        $post['autobackup_cron_month']=  $this->formData['autobackup_cron_month'];
        $post['autobackup_cron_weekday']= $this->formData['autobackup_cron_weekday'];
        $post['auto_backup_rotation']= $this->formData['auto_backup_rotation'];
        $post['select_files_backup']=  $this->formData['select_files_backup'];
        $post['admin_username']= $this->formData['admin_username'];
        $post['admin_pass']= $this->formData['admin_pass'];
        $post['signon_username']= $this->formData['signon_username'];
        $post['theme_id']= $this->formData['theme_id'];
        $post['theme_name']= $this->formData['theme_name'];
        $post['editins']= "Save Installation Details";
        $post['softsubmitbut']= "Save Installation Details";
        $post['loginizer']= $this->formData['loginizer'];
        $post['classic-editor']= $this->formData['classic-editor'];


        $module->installationUpdate($installation->relation_id, (array)$post);
        Helper\infoLog(sprintf('Installation has been updated #%s, Client ID #%s', $installation->id, $request->getSession('uid')));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Installation has been updated successfully');
    }
    public function upgradePlugins()
    {
        $wpId = $this->request->get('wpid') ?: $this->request->get('formData')['installation_id'];
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request = Helper\sl('request');
        /* @var  $installation  Installation */

        $installation = Installation::where('id', $wpId)
        ->where('user_id', $request->getSession('uid'))
        ->firstOrFail();

        $module = Wp::subModule($installation->hosting);
        if ($installation->username)
        {
            $module->setUsername($installation->username);
        }
        return $module->pluginUpdateAll($installation);
    }
    public function upgradeThemes()
    {
        $wpId = $this->request->get('wpid') ?: $this->request->get('formData')['installation_id'];
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request = Helper\sl('request');
        /* @var  $installation  Installation */

        $installation = Installation::where('id', $wpId)
        ->where('user_id', $request->getSession('uid'))
        ->firstOrFail();

        $module = Wp::subModule($installation->hosting);
        if ($installation->username)
        {
            $module->setUsername($installation->username);
        }
        return $module->themeUpdateAll($installation);
    }

    /**
     * Clone Installation
     * @throws \Exception
     */
    public function cloneAct()
    {
        $this->reset();
        $wpid = $this->request->get('wpid') ?? $this->formData['wpid'];

        $this->setInstallationId($wpid);
        $this->setUserId($this->request->getSession('uid'));
        if ($this->getInstallation()->username)
        {
            $this->subModule()->setUsername($this->getInstallation()->username);
        }

        $form                   = $this->formData;
        $form['installationId'] = $this->getInstallation()->relation_id;
        $this->subModule()->domain()->setAttributes(['domain' => $this->formData['softdomain']]);
        if ($form['softdomain'] && $form['softdomain'] != $this->getInstallation()->domain && !$this->subModule()->domain()->exist())
        {
            $this->formData['hostingId'] = $this->getInstallation()->hosting_id;
            $this->domainCreate();
        }
        $sslEnable = in_array($this->formData['softproto'], ['3', '4']);
        $response  = $this->subModule()->installationClone($form);
        if ($sslEnable)
        {
            $arguments = [
                'domain'    => $this->formData['softdomain'],
                'softurl'   => $response['__settings']['softurl'],
                'softpath'  => $response['__settings']['softpath'],
                'hostingId' => $this->getInstallation()->hosting_id,
            ];
            queue(SslEnableJob::class, $arguments);
        }
        $model = $this->synchronize($this->subModule(), $this->getInstallation()->hosting, 30);
        if ($model)
        {
            Helper\infoLog(sprintf('Installation has been cloned #%s, Client ID #%s', $model->id, $model->user_id));
            return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setMessageAndTranslate('Installation has been cloned successfully')->setCallBackFunction('reloadInstallationIndexPage');
        }
        Helper\infoLog(sprintf('Installation clone has been pushed in background, Client ID #%s', $this->request->getSession('uid')));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Clone has been pushed in background')->setCallBackFunction('reloadInstallationIndexPage');
    }

    public function deleteAndRedirect()
    {
        logActivity("Delete and Redirect");
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request = Helper\sl('request');
        /* @var  $installation  Installation */
        $installation = Installation::where('id', $request->get('wpid'))
        ->where('user_id', $request->getSession('uid'))
        ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if ($installation->username)
        {
            $module->setUsername($installation->username);
        }
        $form = $request->get('formData');
        $module->installationDelete($installation->relation_id, (array)$form);
        $installation->delete();
        Helper\infoLog(sprintf('Installation ID #%s has been deleted successfully, Client ID #%s, Hosting ID #%s', $installation->id, $request->getSession('uid'), $installation->hosting_id));

        return (new ResponseTemplates\HtmlDataJsonResponse())
        ->setMessageAndTranslate('Installation has been deleted successfully')
        ->setCallBackFunction('reloadInstallationIndexPage');
    }

    public function delete()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request = Helper\sl('request');
        /* @var  $installation  Installation */

        $wpid = $this->formData['installation_id'] ?? $this->formData['wpid'];
        logActivity("Delete");
        $installation = Installation::where('id', $wpid)
        ->where('user_id', $request->getSession('uid'))
        ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if ($installation->username)
        {
            $module->setUsername($installation->username);
        }
        $module->installationDelete($installation->relation_id, (array)$this->formData);

        $installation->delete();
        if (!$installation->staging)
        {
            Installation::where('staging', $installation->relation_id)
            ->where('user_id', $installation->user_id)
            ->where('hosting_id', $installation->hosting_id)
            ->delete();
        }
        Helper\infoLog(sprintf('Installation ID #%s has been deleted successfully, Client ID #%s, Hosting ID #%s', $installation->id, $request->getSession('uid'), $installation->hosting_id));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Installation has been deleted successfully')->setCallBackFunction('reloadInstallationIndexPage');
    }

    /**
     *
     * @deprecated since version 1.3.0
     */

    public function upgrade()
    {
    }

    public function cacheFlush()
    {
        $wpid = $this->request->get('wpid') ?? $this->formData['wpid'];
        $this->setInstallationId($wpid);
        $this->setUserId($this->request->getSession('uid'));
        if ($this->getInstallation()->username)
        {
            $this->subModule()->setUsername($this->getInstallation()->username);
        }
        $this->subModule()->cacheFlush($this->getInstallation());
        Helper\infoLog(sprintf('Cache has been flushed, Installation ID #%s, Client ID #%s', $this->getInstallation()->id, $this->getInstallation()->user_id));



        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('The cache was flushed');
    }

    public function changeDomain()
    {

        $wpId = $this->request->get('wpid') ?: $this->request->get('formData')['installation_id'];
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request = Helper\sl('request');
        /* @var  $installation  Installation */

        $installation = Installation::where('id', $wpId)
        ->where('user_id', $request->getSession('uid'))
        ->firstOrFail();

        $module = Wp::subModule($installation->hosting);
        if ($installation->username)
        {
            $module->setUsername($installation->username);
        }
        if ($this->formData['backup'] == "on")
        {
            $data = [
                'installationId'  => $installation->relation_id,
                'backupDirectory' => 0,
                'backupDataDir'   => 0,
                'backupDatabase'  => 1,
            ];
            Helper\infoLog(sprintf("Backup creating in progress, Installation ID #%s, Client ID #%s", $installation->id, $installation->user_id));
            $module->backupCreate($data);
        }
        unset($this->formData['backup']);
        if ($module->isSupportChangeDomain())
        {
            $oldDomain = $installation->domain;
            $module->domain()->change($oldDomain, $this->formData['newDomain']);
            $installation->path   = str_replace($installation->domain, $this->formData['newDomain'], $installation->path);
            $installation->domain = $this->formData['newDomain'];
            $installation->url    = str_replace($oldDomain, $this->formData['newDomain'], $installation->url);
            $installation->save();
            $module->getWpCli($installation)->searchReplace($oldDomain, $this->formData['newDomain']);
            $module->getConfig($installation)->set('DOMAIN_CURRENT_SITE', $this->formData['newDomain'], 'constant');
            sleep(4);
            $post = [
                'edit_url' => $installation->url,
                'edit_dir' => $installation->path,
                'noemail'  => 1
            ];
            $module->installationUpdate($installation->relation_id, $post);
            //https
            if ($this->formData['ssl'] == "on")
            {
                $module->ssl()->setInstallation($installation)->on();
                if (!$installation->isHttps())
                {
                    $installation->url = str_replace("http", "https", $installation->url);
                }
                $module->getWpCli($installation)->searchReplace("http", "https");
                $installation->save();
            }
            Helper\infoLog(sprintf("Installation domain '%s' has been changed #%s, Client ID #%s", $installation->domain, $installation->id, $request->getSession('uid')));
            return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setMessageAndTranslate('Domain has been changed')
            ->setCallBackFunction('wpOnChangeDomainAjaxDone');
            //domain is selected
        }
        else
        {
            if ($this->formData['domain'] != '0' && is_numeric($this->formData['domain'])) //Change hosting
            {
                $hosting = Hosting::findOrFail($this->formData['domain']);
                $info    = $module->domainInfo();
                if (!str_contains($installation->path, $info['main_domain']['documentroot']))
                {
                    $m = sl('lang')->absoluteT("The '%s' domain directory is invalid. Please configure documentroot to '%s' and try again");
                    return (new ResponseTemplates\HtmlDataJsonResponse)
                    ->setStatusError()
                    ->setMessage(sprintf($m, $info['main_domain']['documentroot'], $installation->path));
                }
                $oldDomain            = $installation->domain;
                $installation->domain = $hosting->domain;
                $module->getWpCli($installation)->searchReplace($oldDomain, $installation->domain);
                $module->getConfig($installation)->set('DOMAIN_CURRENT_SITE', $hosting->domain, 'constant');
                $installation->save();
                $installation->url = str_replace($oldDomain, $hosting->domain, $installation->url);
                $post              = [
                    'edit_url' => $installation->url,
                    'noemail'  => 1
                ];
                $module->installationUpdate($installation->relation_id, $post);
                $installation->save();
                //https
                if ($this->formData['ssl'] == "on")
                {
                    $module->ssl()->setInstallation($installation)->on();
                    if (!$installation->isHttps())
                    {
                        $installation->url = str_replace("http", "https", $installation->url);
                    }
                    $module->getWpCli($installation)->searchReplace("http", "https");
                    $installation->save();
                }
                Helper\infoLog(sprintf("Installation domain '%s' has been changed #%s, Client ID #%s", $installation->domain, $installation->id, $request->getSession('uid')));
                return (new ResponseTemplates\HtmlDataJsonResponse())
                ->setMessageAndTranslate('Domain has been changed')
                ->setCallBackFunction('wpOnChangeDomainAjaxDone');
            }
            else
            {
                if ($this->formData['domain'] != '0' && is_string($this->formData['domain']))
                {
                    $domainInfo = [];
                    foreach ($module->getAddonDomains() as $addonDomain)
                    {
                        if ($this->formData['domain'] == $addonDomain['domain'])
                        {
                            $domainInfo = $addonDomain;
                            break;
                        }
                    }
                    if (str_contains($installation->path, $info['dir']))
                    {
                        $m = sl('lang')->absoluteT("The '%s' domain directory is invalid. Please configure documentroot to '%s' and try again");
                        return (new ResponseTemplates\HtmlDataJsonResponse)
                        ->setStatusError()
                        ->setMessage(sprintf($m, $info['dir'], $installation->path));
                    }
                    $oldDomain            = $installation->domain;
                    $installation->domain = $this->formData['domain'];
                    $installation->save();
                    $module->getWpCli($installation)->searchReplace($oldDomain, $installation->domain);
                    $module->getConfig($installation)->set('DOMAIN_CURRENT_SITE', $this->formData['domain'], 'constant');
                    $installation->url = str_replace($oldDomain, $this->formData['domain'], $installation->url);
                    $post              = [
                        'edit_url' => $installation->url,
                        'noemail'  => 1
                    ];
                    $module->installationUpdate($installation->relation_id, $post);
                    $installation->save();
                    //https
                    if ($this->formData['ssl'] == "on")
                    {
                        $module->ssl()->setInstallation($installation)->on();
                        if (!$installation->isHttps())
                        {
                            $installation->url = str_replace("http", "https", $installation->url);
                        }
                        $module->getWpCli($installation)->searchReplace("http", "https");
                        $installation->save();
                    }
                    Helper\infoLog(sprintf("Installation domain '%s' has been changed #%s, Client ID #%s", $installation->domain, $installation->id, $request->getSession('uid')));
                    return (new ResponseTemplates\HtmlDataJsonResponse())
                    ->setMessageAndTranslate('Domain has been changed')
                    ->setCallBackFunction('wpOnChangeDomainAjaxDone');
                }
                else
                {
                    /** @var  Installation $installation */
                    //add new domain
                    $form = $this->formData;
                    unset($form['domain']);
                    $form['path']      = $installation->path;
                    $ex                = explode(".", $this->formData['newDomain']);
                    $subdomain         = $ex[0];
                    $form['subDomain'] = $subdomain;
                    $form['path']      = sprintf("/%s", $this->formData['newDomain']);
                    $domainService     = $module->domain()->setAttributes(
                        [
                            "dir"        => $installation->path,
                            "domain"     => $this->formData['newDomain'],
                            'subdomain'  => $subdomain,
                            "rootdomain" => $module->getParams()['domain']
                        ]
                    );
                    if (!$domainService->exist())
                    {
                        $domainService->create();
                    }
                    $oldDomain            = $installation->domain;
                    $installation->domain = $this->formData['newDomain'];
                    $installation->save();
                    sleep(4);
                    $module->getWpCli($installation)->searchReplace($oldDomain, $this->formData['newDomain']);
                    $module->getConfig($installation)->set('DOMAIN_CURRENT_SITE', $this->formData['newDomain'], 'constant');
                    $installation->url = str_replace($oldDomain, $this->formData['newDomain'], $installation->url);
                    $post              = [
                        'edit_url' => $installation->url,
                        'noemail'  => 1
                    ];
                    if ($module instanceof Plesk)
                    {
                        $softaculous = SoftaculousFactory::fromParamsAsUser($module->getParams());
                        $site        = $module->domain()->findDomain($installation->domain);
                        if ($site['id'])
                        {
                            $softaculous->getProvider()->setCookieSoftdomId($site['id']);
                        }
                        $softaculous->installationUpdate($installation->relation_id, $post);
                        $installation->domain_id = $site['id'];
                    }
                    else
                    {
                        $module->installationUpdate($installation->relation_id, $post);
                    }
                    $installation->save();
                    //https
                    if ($this->formData['ssl'] == "on")
                    {
                        $module->ssl()->setInstallation($installation)->on();
                        if (!$installation->isHttps())
                        {
                            $installation->url = str_replace("http", "https", $installation->url);
                        }
                        $module->getWpCli($installation)->searchReplace("http", "https");
                        $installation->save();
                    }
                    Helper\infoLog(sprintf("New domain '%s' has been added, Installation ID %s, Client ID #%s", $this->formData['newDomain'], $installation->id, $request->getSession('uid')));
                    return (new ResponseTemplates\HtmlDataJsonResponse())
                    ->setMessageAndTranslate('New domain has been added')
                    ->setCallBackFunction('wpOnChangeDomainAjaxDone');
                }
            }
        }
    }

    protected function createWebsiteDetails()
    {
        $inst = Installation::pluck('id')->max();
        if ($inst == null)
        {
            $inst = 1;
        }
        (new WebsiteDetails(['wpid' => $inst]))->save();
    }
}
