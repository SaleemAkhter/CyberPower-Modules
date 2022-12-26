<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations;

use ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Helper\CheckWpVersion;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Models\ModuleSettings;
use ModulesGarden\WordpressManager\App\Models\ProductSetting;
use ModulesGarden\WordpressManager\App\Models\WebsiteDetails;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\InstanceImageButton;
use ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\PushToLiveButton;
use ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\SslButton;
use ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\StagingButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\ChangeDomainButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\CloneButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\InstallationUpdateButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\UpgradeButton;
use ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonBase;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonRedirect;
use function ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager\App\UI\Installations\Providers\InstallationProvider;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use ModulesGarden\WordpressManager\Core\Helper;

class InstallationDetailsPageC extends BaseContainer implements ClientArea, AjaxElementInterface
{
    use RequestObjectHandler;
    use main\Core\UI\Traits\Buttons;
    use main\App\Http\Client\BaseClientController;
    use \ModulesGarden\WordpressManager\Core\UI\Traits\FormDataProvider;

    protected $id = 'mg-wp-installation-details';
    protected $name = 'mg-wp-installation-details-name';
    protected $title = 'mg-wp-installation-details-title';

    protected $vueComponent = true;
    protected $defaultVueComponentName = 'mg-wp-installation-detailsC';

    private $settings;

    public function initContent()
    {

        if(isset($_GET['index']) && $_GET['index']=="updateSiteInfo"){
            $response=  $this->updateSiteInfo();
            echo json_encode(['data'=>$response->getData()]);
            exit;
        }elseif(isset($_GET['index']) && $_GET['index']=="updateSiteConfig"){

            $response=  $this->updateSiteConfig();
            echo json_encode(['data'=>$response->getData()]);
            exit;
        }elseif(isset($_GET['ajax']) && $_GET['ajax']==1){
             $this->returnAjaxData();
        }else{
            return $this->returnAjaxButtons();
        }
    }
    public function updateSiteInfo()
    {
        $data=$_POST['formData'];
        $this->setProvider(new InstallationProvider);
        return $this->dataProvider->updateSiteInfo();
    }
    public function updateSiteConfig()
    {
        $data=$_POST['formData'];
        $this->setProvider(new InstallationProvider);
        return $this->dataProvider->updateSiteConfig();
    }
    public function returnAjaxButtons()
    {
        $this->loadRequestObj();
        $wpLastVersion = ((new ModuleSettings())->getSettings())['wordpressVersion'];
        $request       = sl('request');
        $installationdetails=[];
        $uid = $this->request->getSession('uid');

        $i     = (new Installation)->getTable();
        $p     = (new Product())->getTable();
        $h     = (new Hosting())->getTable();
        $wd    = (new WebsiteDetails)->getTable();
        $ps    = (new ProductSetting())->getTable();
        $query = (new Installation)
        ->query()
        ->getQuery()
        ->rightJoin($h, "{$i}.hosting_id", '=', "{$h}.id")
        ->rightJoin($p, "{$h}.packageid", '=', "{$p}.id")
        ->leftJoin($wd, "{$i}.id", '=', "{$wd}.wpid")
        ->leftJoin($ps, "{$h}.packageid", '=', "{$ps}.product_id")
        ->select(
            "{$i}.id",
            "{$i}.domain",
            "{$i}.site_name",
            "{$i}.hosting_id",
            "{$i}.url",
            "{$i}.path",
            "{$i}.version",
            "{$i}.staging",
            "{$i}.created_at",
            "{$i}.username",
            "{$i}.auto",
            "{$p}.name",
            "{$h}.packageid",
            "{$wd}.screenshot",
            "{$ps}.settings"
        )
        ->where("{$i}.user_id", $request->getSession('uid'))
        ->where("{$h}.domainstatus", "Active")
        ->orderBy("$i.domain");


        $rd      = new ButtonRedirect;
        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
        'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=details'
        : BuildUrl::getUrl('home', 'detail',['id'=>$_GET['id']]);
        $rd->setRawUrl($baseUrl)
        ->setIcon('')
        ->setId('editDetails')
        ->resetClass()
        ->addClass('lu-btn')
        ->addClass('lu-btn--primary')->setShowTitle(true);

        $this->addButton(($rd)->setMainContainer($this->mainContainer));


        //ControlPanel
        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
        'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=controlPanel'
        : BuildUrl::getUrl('home', 'controlPanel',['id'=>$_GET['id']]);

        $cp = new ButtonRedirect('controlPanel');

        $cp->setRawUrl($baseUrl)
        ->setIcon('')
        ->setHtmlAttributes(['target'=>"_blank"])
        ->resetClass()
        ->addClass('lu-align-middle')
        ->addClass('lu-btn')
        ->addClass('lu-btn--primary')->setShowTitle(true);
        $cp->setShowByColumnValue('actionscontrolpanel', '1');

        $this->addButton(($cp)->setMainContainer($this->mainContainer));

        $this->addButton((new ChangeDomainButton())
            ->setIcon('')
            ->resetClass()
            ->setTitle("changeDomainButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--primary')
            ->setMainContainer($this->mainContainer));

        $this->addButton((new CloneButton())
            ->setIcon('')
            ->resetClass()
            ->setTitle("cloneButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--primary')
            ->setMainContainer($this->mainContainer));

        $this->addButton((new InstallationUpdateButton())
            ->setIcon('')
            ->resetClass()
            ->setTitle("installationUpdateButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--success')->setMainContainer($this->mainContainer));

        $this->addButton((new StagingButton())
            ->setIcon('')
            ->resetClass()
            ->setTitle("stagingButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--primary')
            ->setMainContainer($this->mainContainer));
        $this->addButton((new UpgradeButton())
            ->setIcon('')
            ->resetClass()
            ->setTitle("upgradeButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--xs')
            ->addClass('lu-btn--primary')->setMainContainer($this->mainContainer));
        $pushToLive = $this->addButton((new PushToLiveButton())
            ->setIcon('')
            ->resetClass()
            ->setTitle("pushToLiveButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--success')->setMainContainer($this->mainContainer));
$this->addButton((new main\App\UI\Installations\Buttons\MaintenanceModeButton('maintenanceMode'))
            ->setIcon('')
            ->resetClass()
            ->setTitle("maintenanceModeButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--success')->setMainContainer($this->mainContainer));
        $this->addButton((new SslButton())
            ->setIcon('')
            ->resetClass()
            ->setTitle("sslButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--success')->setMainContainer($this->mainContainer));
        /*Cache*/
        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
        'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=config'
        : BuildUrl::getUrl('home', 'config',['id'=>$_GET['id']]);

        $cp = new ButtonRedirect('configButton');

        $cp->setRawUrl($baseUrl)
        ->setIcon('')
        ->resetClass()
        ->setTitle("configButton")
        ->addClass('lu-btn')
        ->addClass('lu-btn--primary')->setShowTitle(true);

        $this->addButton(($cp)->setMainContainer($this->mainContainer));



        $this->addButton((new InstanceImageButton())
            ->setIcon('')
            ->resetClass()
            ->setTitle("instanceImageButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--success')->setMainContainer($this->mainContainer));

       $this->addButton((new main\App\UI\Installations\Buttons\CacheButton('cache'))
            ->setIcon('')
            ->resetClass()
            ->setTitle("clearCacheButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--primary')
            ->setMainContainer($this->mainContainer));

        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
        'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=backups'
        : BuildUrl::getUrl('home', 'backups',['id'=>$_GET['id']]);

        $cp = new ButtonRedirect('backupButton');

        $cp->setRawUrl($baseUrl)
        ->setIcon('')
        ->resetClass()
        ->setTitle("backupButton")
        ->addClass('lu-btn')
        ->addClass('lu-btn--success')->setShowTitle(true);

        $this->addButton(($cp)->setMainContainer($this->mainContainer));

        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
        'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=backups&restore=1'
        : BuildUrl::getUrl('home', 'backups',['id'=>$_GET['id']]);

        $cp = new ButtonRedirect('restoreButton');

        $cp->setRawUrl($baseUrl)
        ->setIcon('')
        ->setId('restoreButton')
        ->resetClass()
        ->setTitle("restoreButton")
        ->addClass('lu-btn')
        ->addClass('lu-btn--success')->setShowTitle(true);

        $this->addButton(($cp)->setMainContainer($this->mainContainer));



        // ConfigButton
         $this->addButton((new Buttons\InstallationDeleteButton())
            ->setIcon('')
            ->resetClass()
            ->setTitle("installationDeleteButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--dange')
            ->setMainContainer($this->mainContainer));

        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
        'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=themes'
        : BuildUrl::getUrl('home', 'themes',['id'=>$_GET['id']]);

        $cp = new ButtonRedirect('themesButton');

        $cp->setRawUrl($baseUrl)
        ->setIcon('')
        ->resetClass()
        ->setTitle("themesButton")
        ->addClass('lu-btn')
        ->addClass('lu-btn--success float-right')->setShowTitle(true);

        $this->addButton(($cp)->setMainContainer($this->mainContainer));
        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
        'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=plugins'
        : BuildUrl::getUrl('home', 'plugins',['id'=>$_GET['id']]);

        $cp = new ButtonRedirect('pluginsButton');

        $cp->setRawUrl($baseUrl)
        ->setIcon('')
        ->resetClass()
        ->setTitle("pluginsButton")
        ->addClass('lu-btn')
        ->addClass('lu-btn--success float-right')->setShowTitle(true);

        $this->addButton(($cp)->setMainContainer($this->mainContainer));

        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
        'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=users'
        : BuildUrl::getUrl('home', 'users',['id'=>$_GET['id']]);

        $cp = new ButtonRedirect('usersButton');

        $cp->setRawUrl($baseUrl)
        ->setIcon('')
        ->setId("usersButton")
        ->resetClass()
        ->setTitle("usersButton")
        ->addClass('lu-btn')
        ->addClass('lu-btn--success')->setShowTitle(true);

        $this->addButton(($cp)->setMainContainer($this->mainContainer));




        // $this->addButton((new main\App\UI\Installations\Buttons\BackupButton())
        //     ->setIcon('')
        //     ->resetClass()
        //     ->setTitle("backupButton")
        //     ->addClass('lu-btn')
        //     ->addClass('lu-btn--warning')
        //     ->setMainContainer($this->mainContainer));
        return (new RawDataJsonResponse(['data' => []]));
    }
    public function getAvailableOpts(){
        return [
                0 => Helper\sl("lang")->abtr("Do not auto upgrade"),
                1 => Helper\sl("lang")->abtr("Upgrade to any latest version available (Major as well as Minor)"),
                2 => Helper\sl("lang")->abtr("Upgrade to Minor versions only"),
            ];
    }
    public function getManagePluginsButton($wpid)
    {
        // http://cyberpower.test/index.php?m=WordpressManager&mg-page=home&mg-action=plugins&wpid=118
        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
        'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=controlPanel'
        : BuildUrl::getUrl('home', 'plugins');

        $cp = new ButtonRedirect('controlPanel');

        $cp->setRawUrl($baseUrl)
        ->setRedirectParams(['wpid'->$wpid])
        ->setIcon('')
        ->resetClass()
        ->addClass('lu-btn')
        ->addClass('lu-btn--success')->setShowTitle(true);
        return $cp->getHtml();
    }
    public function returnAjaxData()
    {
        $username=array_pop(array_reverse($_SESSION['resellerloginas']));

        $this->loadRequestObj();
        $wpLastVersion = ((new ModuleSettings())->getSettings())['wordpressVersion'];
        $request       = sl('request');
        $installationdetails=[];
        $uid = $this->request->getSession('uid');

        $i     = (new Installation)->getTable();
        $p     = (new Product())->getTable();
        $h     = (new Hosting())->getTable();
        $wd    = (new WebsiteDetails)->getTable();
        $ps    = (new ProductSetting())->getTable();
        $query = (new Installation)
        ->query()
        ->getQuery()
        ->rightJoin($h, "{$i}.hosting_id", '=', "{$h}.id")
        ->rightJoin($p, "{$h}.packageid", '=', "{$p}.id")
        ->leftJoin($wd, "{$i}.id", '=', "{$wd}.wpid")
        ->leftJoin($ps, "{$h}.packageid", '=', "{$ps}.product_id")
        ->select(
            "{$i}.id",
            "{$i}.domain",
            "{$i}.site_name",
            "{$i}.hosting_id",
            "{$i}.url",
            "{$i}.path",
            "{$i}.version",
            "{$i}.staging",
            "{$i}.created_at",
            "{$i}.username",
            "{$i}.auto",
            "{$p}.name",
            "{$h}.packageid",
            "{$wd}.screenshot",
            "{$ps}.settings"
        )
        ->where("{$i}.user_id", $request->getSession('uid'))
        ->where("{$i}.username",$username)
        ->where("{$h}.domainstatus", "Active")
        ->orderBy("$i.id","ASC");

        if ($this->request->get('hostingId'))
        {
            $data = [
                'installations' => $query->where("{$i}.user_id", $uid)
                ->where("{$i}.hosting_id", $this->request->get('hostingId'))->get(),
            ];
        }
        else
        {

            $data = [
                'installations' => $query->where("{$i}.user_id", $uid)->get(),
            ];
        }
        foreach ($data['installations'] as $key=>$installation)
        {
            $installation->staging = $installation->staging ? 1 : 0;
            $installation->isOld   = version_compare($wpLastVersion, $installation->version);
            $decoded               = json_decode($installation->settings);

            foreach ($decoded as $fieldName => $value)
            {
                if (stripos($fieldName, 'actions-') !== 0)
                {
                    continue;
                }

                $jsField                = str_replace('-', '', $fieldName);
                $installation->$jsField = $value;

            }
            $data['installations'][$key]->settings=$decoded;
            $data['installations'][$key]->showDbDetails = false;
            if($key==0){
                $data['installations'][$key]->opened = true;

            }else{
                $data['installations'][$key]->opened = false;
                $additionalData['db']="";
                $additionalData['dbHost']="";
                $additionalData['dbUser']="";
                $additionalData['eu_auto_upgrade']='';
                $additionalData['disable_wp_cron']='';
                $additionalData['auto_upgrade_plugins']='';
                $additionalData['auto_upgrade_themes'] ='';
                $additionalData['eu_auto_upgrade']='';
                // $data['installations'][$key]->additionalData = $additionalData;
            }
            $installation = Installation::where('id', $installation->id)
                ->where('user_id', $this->request->getSession('uid'))
                ->firstOrFail();
                $module       = Wp::subModule($installation->hosting);
                if ($installation->username)
                {
                    $module->setUsername($installation->username);
                }
                if(isset($installationdetails[$installation->relation_id])){
                    $details = $installationdetails[$installation->relation_id];
                }else{
                    $details = $module->installationDetail($installation->relation_id);
                }

                // if(isset($details['wordpressins'])){
                //     $installationdetails=$details['wordpressins'];
                // }
            // debug($details);die();
                /*Maintenance Mode*/
                $maintenanceMode = ($module->getWpCli($installation))->maintenance();
                $additionalData['maintenanceMode'] = ((isset($details['blog_public']) && $details['blog_public']==1) || (isset($details['wp_debug']) && $details['wp_debug']==1))?1:0;
                $additionalData['db']=$details['db'];
                $additionalData['dbHost']=$details['dbHost'];
                $additionalData['dbUser']=$details['dbUser'];

                $additionalData['eu_auto_upgrade']            = $details['eu_auto_upgrade'];
                $additionalData['eu_auto_upgrade'] = [
                    0 => Helper\sl("lang")->abtr("Do not auto upgrade"),
                    1 => Helper\sl("lang")->abtr("Upgrade to any latest version available (Major as well as Minor)"),
                    2 => Helper\sl("lang")->abtr("Upgrade to Minor versions only"),
                ];

                $additionalData['eu_auto_upgrade'] = $details['eu_auto_upgrade'];
                $additionalData['disable_wp_cron'] = $details['disable_wp_cron'];

                $additionalData['auto_upgrade_plugins'] = $details['auto_upgrade_plugins'] ? "on" : "off";
                $additionalData['auto_upgrade_themes']  = $details['auto_upgrade_themes'] ? "on" : "off";

                $data['installations'][$key]->additionalData = $additionalData;
                $data['installations'][$key]->rawadditional = $details;
            // $additionalData['db']="qqq";
            // $additionalData['dbHost']="";
            // $additionalData['dbUser']="";
            // $additionalData['eu_auto_upgrade']='';
            // $additionalData['disable_wp_cron']='';
            // $additionalData['auto_upgrade_plugins']='';
            // $additionalData['auto_upgrade_themes'] ='';
            // $additionalData['eu_auto_upgrade']='';
            // $data['installations'][$key]->additionalData = $additionalData;
            // $data['installations'][$key]->rawadditional = $additionalData;


        }

        //website details
        $rd      = new ButtonRedirect;
        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
        'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=details'
        : BuildUrl::getUrl('home', 'detail');
        $rd->setRawUrl($baseUrl)
        ->setIcon('')
        ->resetClass()
        ->addClass('lu-btn')
        ->addClass('lu-btn--success')->setShowTitle(true);

        $this->addButton(($rd)->setMainContainer($this->mainContainer));


        //ControlPanel
        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
        'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=controlPanel'
        : BuildUrl::getUrl('home', 'controlPanel');

        $cp = new ButtonRedirect('controlPanel');

        $cp->setRawUrl($baseUrl)
        ->setIcon('')
        ->resetClass()
        ->addClass('lu-btn')
        ->addClass('lu-btn--success')->setShowTitle(true);
        $cp->setShowByColumnValue('actionscontrolpanel', '1');

        $this->addButton(($cp)->setMainContainer($this->mainContainer));

        $this->addButton((new main\App\UI\Installations\Buttons\CacheButton('cache'))
            ->setIcon('')
            ->resetClass()
            ->setTitle("clearCacheButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--success')
            ->setMainContainer($this->mainContainer));

        $this->addButton((new CloneButton('clone'))
            ->setIcon('')
            ->resetClass()
            ->setTitle("cloneButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--success')->setMainContainer($this->mainContainer));

        $this->addButton((new UpgradeButton())
            ->setIcon('')
            ->resetClass()
            ->setTitle("upgradeButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--success')->setMainContainer($this->mainContainer));

        $this->addButton((new ChangeDomainButton())
            ->setIcon('')
            ->resetClass()
            ->setTitle("changeDomainButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--success')->setMainContainer($this->mainContainer));

        $this->addButton((new InstallationUpdateButton())
            ->setIcon('')
            ->resetClass()
            ->setTitle("installationUpdateButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--success')->setMainContainer($this->mainContainer));

        $this->addButton((new StagingButton())
            ->setIcon('')
            ->resetClass()
            ->setTitle("stagingButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--success')
            ->setMainContainer($this->mainContainer));

        $pushToLive = $this->addButton((new PushToLiveButton())
            ->setIcon('')
            ->resetClass()
            ->setTitle("pushToLiveButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--success')->setMainContainer($this->mainContainer));

        $this->addButton((new SslButton())
            ->setIcon('')
            ->resetClass()
            ->setTitle("sslButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--success')->setMainContainer($this->mainContainer));

        $this->addButton((new main\App\UI\Installations\Buttons\MaintenanceModeButton('maintenanceMode'))
            ->setIcon('')
            ->resetClass()
            ->setTitle("maintenanceModeButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--success')->setMainContainer($this->mainContainer));

        $this->addButton((new InstanceImageButton())
            ->setIcon('')
            ->resetClass()
            ->setTitle("instanceImageButton")
            ->addClass('lu-btn')
            ->addClass('lu-btn--success')->setMainContainer($this->mainContainer));



        return (new RawDataJsonResponse(['data' => $data]));
    }


    public function replaceFieldVersion($key, $row)
    {
        $result = (new CheckWpVersion())->checkIfNewer($row->version);

        if ($result)
        {
            $rd = new ButtonBase;
            $rd->setName('updateWpAlert');
            $rd->setTitle('updateWpAlert');
            $rd->replaceClasses(['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--danger redAlert vertical-align-middle']);
            $rd->setIcon('lu-btn__icon lu-zmdi lu-zmdi-alert-circle');

            return $row->version . $rd;
        }

        return $row->version;
    }
}
