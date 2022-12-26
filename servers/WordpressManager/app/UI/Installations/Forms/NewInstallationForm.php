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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Forms;

use ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Helper\Decorator;
use ModulesGarden\WordpressManager\App\Helper\UtilityHelper;
use ModulesGarden\WordpressManager\App\Models\PluginPackage;
use ModulesGarden\WordpressManager\App\Models\CustomPlugin;
use ModulesGarden\WordpressManager\App\Repositories\HostingRepository;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\InstallationProvider;
use ModulesGarden\WordpressManager\Core\FileReader\Reader\Json;
use ModulesGarden\WordpressManager\Core\ModuleConstants;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseTabsForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\HalfPageSection;
use function ModulesGarden\WordpressManager\Core\Helper\sl;
use ModulesGarden\WordpressManager\App\UI\Installations\Fields\TitleField;
use ModulesGarden\WordpressManager\App\UI\Installations\Sections\RowSection;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonSubmitForm;
use ModulesGarden\WordpressManager\App\UI\Installations\Fields\SoftwareSetup;
use ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\WordpressManager\App\UI\Installations\Fields\PrefixedText;
/**
 * Description of CreateForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class NewInstallationForm extends BaseTabsForm implements ClientArea
{
    protected $formData;
    protected $formFields;
    use main\App\Http\Client\BaseClientController;

    // protected $modal;

    /**
     * InstallationCreateForm constructor.
     * @param main\App\UI\Installations\Modals\InstallationCreateModal $modal
     */
    // public function __construct(main\App\UI\Installations\Modals\InstallationCreateModal $modal)
    // {
    //     $this->modal    = $modal;

    //     parent::__construct();
    // }

    public function initContent()
    {
        $this->loadRequestObj();
        $this->formData = $this->request->get('formData');
        $this->initIds('installationCreateForm');
        if($this->isLimitReached())
        {
            $this->modal->removeActionButtonByIndex('baseAcceptButton');
            return;
        }
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new InstallationProvider());
        $this->tabDetails();

        $submitButton = new ButtonSubmitForm();
        $submitButton->setFormId($this->id);
        $submitButton->runInitContentProcess();
        // debug($submitButton);die();
        $this->setSubmit($submitButton);
        $this->loadDataToForm();
    }

    private function isLimitReached()
    {
        $repository = new HostingRepository;
        $hostings   = $repository->findEnabledWithProduct($this->request->getSession('uid'), $this->request->get('hostingId', null));
        if(empty($hostings))
        {
            $this->setInternalAlertMessage('allHostingsLimited', ['title' => null]);
            return true;
        }

        return false;
    }

    private function tabDetails()
    {
        $repository = new HostingRepository;
        $request       = sl('request');
        $hostings   = $repository->findEnabledWithProduct($this->request->getSession('uid'), $this->request->get('hostingId', null));
        if(empty($hostings))
        {
            $this->setInternalAlertMessage('allHostingsLimited', ['title' => null]);
            return;
        }
        $loggedinuser=array_pop(array_reverse($_SESSION['resellerloginas']));



        $section = new Sections\TabSection();
        $section->initIds(__FUNCTION__);
        $section->enableGroupBySectionName();
        $section->setMainContainer($this->mainContainer);

        $section->setName(sl('lang')->T(__FUNCTION__));
        $content     = new Sections\RawSection();

        $softwaresection=(new Sections\BoxSection())->setTitle("Software Setup")->setMainContainer($this->mainContainer);

        $secondRow    = (new RowSection('secondRow'));
        $thirdRow     = new RowSection('thirdRow');
        $forthRow     = (new Sections\BoxSection())->setId("fourthRow")->setTitle("Advanced Options")->enableCustomCollapse()->setMainContainer($this->mainContainer);
        $siteSettingsSection=new HalfPageSection('sitesettings');

        $siteSettingsBox=(new Sections\BoxSection())->setId('siteSettingBox')->setTitle("Site Settings");

        $adminAccountSection=(new HalfPageSection('adminaccount'))->setId('adminaccount');
        $adminAccountBox=(new Sections\BoxSection())->setId('adminAccountBox')->setTitle("Admin Account");

        $languageSection=(new HalfPageSection('languageSection'));
        $languageBox=(new Sections\BoxSection())->setId('languageBox')->setTitle('Choose Language');

        $pluginsSection=(new HalfPageSection('pluginsSection'));
        $pluginsBox=(new Sections\BoxSection())->addClass('list')->setId('pluginsBox')->setTitle('Select Plugin(s)')->setMainContainer($this->mainContainer);


        $advancedrightSide   = (new HalfPageSection('advancedrightSide'))->setId('advancedrightSide');

        $advancedleftSide    = (new HalfPageSection('advancedleftSide'))->setId('advancedleftSide');


        $mainSection = new Sections\RawSection('mainSection');

        $content->setMainContainer($this->mainContainer);

        $mainSection->setMainContainer($this->mainContainer);
        $adminAccountBox->setMainContainer($this->mainContainer);




        if (!$this->formData['hostingId'])
        {
            $this->formData['hostingId'] = key($hostings);
        }

        $field = new Fields\Select('hostingId');
        $field->initIds('hostingId');
        $field->setDescription('description');
        $field->setAvailableValues((array)$hostings);
        $field->addHtmlAttribute('bi-event-change', "reloadVueModal");
        // $softwaresection->addField($field);
        $this->reset()
            ->setHostingId($this->formData['hostingId'])
            ->setUserId($this->request->getSession('uid'));

        $this->subModule()->setUsername($loggedinuser);
        $domains=$this->subModule()->domain()->list();
        //username


        $field = (new SoftwareSetup())->setDomains($domains);
        // $field->setAvailableValues((new Decorator())->getProtocols());
        // $field->setDefaultValue(3);
        $softwaresection->addField($field);

        //
        //softproto
        $field = new Fields\Select('softproto');
        $field->setAvailableValues((new Decorator())->getProtocols());
        $field->setDefaultValue(3);
        // $softwaresection->addField($field);
        //softdomain
        $field = new Fields\Text('softdomain');
        $field->setDescription('description');
        $field->notEmpty();
        // $softwaresection->addField($field);
        //installation script
        $installationScripts = [];
        if ($this->getHostingId())
        {
            $installationScripts = $this->getHosting()->productSettings->getSettings()['installationScripts'];
        }
        $total = count($installationScripts);
        if ($total > 1)
        {
            $field   = new Fields\Select('installationScript');
            $options = [];
            foreach ($installationScripts as $appId => $app)
            {
                $options[$appId] = sl('lang')->absoluteT($app);
                if ($total <= 1)
                {
                    $field->setDefaultValue($appId);
                }
            }
            $field->setAvailableValues($options);
            $field->setDescription('description');
            $softwaresection->addField($field);
        }
        else
        {
            if ($total == 1)
            {
                $field = new Fields\Hidden('installationScript');
                $field->setDefaultValue(key($installationScripts));
                $softwaresection->addField($field);
            }
        }

        //site_name
        $field = new Fields\Text('site_name');
        $field->setDefaultValue(sl('lang')->T('My WordPress'));
        $field->notEmpty();
        $siteSettingsBox->addField($field);


        $field = (new Fields\Text('site_desc'));
        $siteSettingsBox->addField($field);

        //multisite on off
        $field = (new Fields\Switcher('multisite'))->setDescription('description');
        $siteSettingsBox->addField($field);


        $field = (new Fields\Switcher('disable_wp_cron'))->setDescription('description');
        $siteSettingsBox->addField($field);



         //admin_username
        $adminAccountBox->addField((new Fields\Text('admin_username'))->setDefaultValue('admin'));

        //admin_pass
        $adminPassword = UtilityHelper::generatePassword(14,'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+=-');
        $adminAccountBox->addField((new Fields\PasswordGenerate('admin_pass'))->setDefaultValue($adminPassword));

        //admin_email
        $field = new Fields\Text('admin_email');
        $field->addHtmlAttribute('id','admin_email');
        $adminAccountBox->addField($field);

        //language
        $json  = new Json('softaculousLanguages.json', ModuleConstants::getDevConfigDir());
        $field = new Fields\Select('language');
        $field->setAvailableValues((array)$json->get());
        $field->setDefaultValue((new main\App\Helper\LangConveter())->convert());
        $languageBox->addField($field);

        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
        'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=manageSets'
        : BuildUrl::getUrl('home', 'manageSets');


        if ($this->getHostingId() && count((array)$this->getHosting()->productSettings->getPluginPackages()))
        {

            $packages = PluginPackage::ofId($this->getHosting()->productSettings->getPluginPackages())
                ->enable()
                ->select("name","description", "id")->get();

            foreach ($packages as $key => $package) {
                if($key==0){
                    $field = (new Fields\Switcher('pluginPackages'.$package->id))
                    ->setName('pluginPackages')
                    ->setRawTitle($package->name)
                    ->setSwitchFieldLabel('Packages')
                    ->setSwitchLabelDescription('packagesDescription')
                    ->setDescription($package->description);
                }else{
                    $field = (new Fields\Switcher('pluginPackages'.$package->id))
                    ->setName('pluginPackages')
                    ->setRawTitle($package->name)
                    ->setDescription($package->description);
                }

                $field->setHtmlAttributes(['value'=>$package->id]);
                $pluginsBox->addField($field);
            }
        }
        if ($this->getHostingId() && count((array)$this->getHosting()->productSettings->getCustomPlugins()))
        {

            $plugins = CustomPlugin::ofId($this->getHosting()->productSettings->getCustomPlugins())
                ->enable()
                ->select("name", "id","description");
            $counter=0;
            foreach ($plugins->get() as $key => $plugin) {
                if($counter==0){
                    $field = (new Fields\Switcher('customPlugins'.$plugin->id))
                    ->setName('customPlugins')
                    ->setRawTitle($plugin->name)
                    ->setSwitchFieldLabel('CustomPlugins')
                    ->setDescription($plugin->description);
                }else{
                    $field = (new Fields\Switcher('customPlugins'.$plugin->id))
                    ->setName('customPlugins')
                    ->setRawTitle($plugin->name)
                    ->setDescription($plugin->description);
                }

                $field->setHtmlAttributes(['value'=>$plugin->id]);
                $pluginsBox->addField($field);
                $counter++;
            }

        }



        $field = (new Fields\Switcher('loginizer'))->setDescription('description');
        $pluginsBox->addField($field);
        //auto_upgrade_plugins
        $field = (new Fields\Switcher('classic-editor'))->setDescription('description');
        $pluginsBox->addField($field);

        $dbName = "wp".UtilityHelper::generatePassword(3,'abcdefghijklmnopqrstuvwxyz123456789');

        //softdb
        $field = (new PrefixedText('softdb'))->setPrefix($loggedinuser."_");
        $field->notEmpty();
        $field->setDefaultValue($dbName);
        $advancedleftSide->addField($field);

        //auto_upgrade_plugins
        $field = (new Fields\Switcher('disable_notify_update'))->setDescription('description');
        $advancedleftSide->addField($field);
        //auto_upgrade_plugins
        $field = (new Fields\Switcher('auto_upgrade_plugins'))->setDescription('description');
        $advancedleftSide->addField($field);
        //auto_upgrade_themes
        $field = (new Fields\Switcher('auto_upgrade_themes'))->setDescription('description');
        $advancedleftSide->addField($field);

        //eu_auto_upgrade
        $field = (new Fields\Select('backup_location'))->setDescription('description');
        $field->setAvailableValues([
            0 => sl("lang")->abtr("Default"),
            -1 => sl("lang")->abtr("Local Folder"),
        ]);
        $advancedleftSide->addField($field);

        //eu_auto_upgrade
        $field = (new Fields\Select('auto_backup_rotation'))->setDescription('description');
        $field->setAvailableValues([
            0 => sl("lang")->abtr("Unlimited"),
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
            10 => 10
        ]);
        $advancedleftSide->addField($field);

         //dbprefix
        $dbprefix = "wp".UtilityHelper::generatePassword(2,'abcdefghijklmnopqrstuvwxyz123456789')."_";
        $field = new Fields\Text('dbprefix');
        $field->setDefaultValue($dbprefix);
        $advancedrightSide->addField($field);

        //softdirectory
        $field = new Fields\Text('softdirectory');
        $field->setDescription('description');
        // $advancedrightSide->addField($field);


        //eu_auto_upgrade
        $field = (new Fields\Select('eu_auto_upgrade'))->setDescription('description');
        $field->setAvailableValues([
            0 => sl("lang")->abtr("Do not auto upgrade"),
            1 => sl("lang")->abtr("Upgrade to any latest version available (Major as well as Minor)"),
            2 => sl("lang")->abtr("Upgrade to Minor versions only"),
        ]);
        $advancedrightSide->addField($field);


//eu_auto_upgrade
        $field = (new Fields\Select('auto_backup'))->setFormGroupClass('mt-60')->setDescription('description');
        $field->setAvailableValues([
            0 => sl("lang")->abtr("Don't backup"),
            'daily' => sl("lang")->abtr("Once a day"),
            'weekly' => sl("lang")->abtr("Once a week"),
            'monthly' => sl("lang")->abtr("Once a month"),
            'custom' => sl("lang")->abtr("Custom"),
        ]);
        $advancedrightSide->addField($field);




        $content->addSection($softwaresection);


        $siteSettingsSection->addSection($siteSettingsBox);
        $adminAccountSection->addSection($adminAccountBox);
        $secondRow->addSection($siteSettingsSection);
        $secondRow->addSection($adminAccountSection);
        $content->addSection($secondRow);

        $languageSection->addSection($languageBox);
        $pluginsSection->addSection($pluginsBox);

        $thirdRow->addSection($languageSection);
        $thirdRow->addSection($pluginsSection);


        $forthRow->addSection($advancedleftSide);
        $forthRow->addSection($advancedrightSide);
        $content->addSection($thirdRow);
        $content->addSection($forthRow);
        $section->addSection($content);
        $this->addSection($section);

        //Add other tab
        // $this->tabOther();
    }

    private function tabOther()
    {
        $section = new Sections\TabSection();
        $section->initIds(__FUNCTION__);
        $section->enableGroupBySectionName();
        $section->setMainContainer($this->mainContainer);
        $section->setName(sl('lang')->T(__FUNCTION__));
        $content = new Sections\RawSection();
        //site_desc


        $section->addSection($content);
        $this->addSection($section);
    }
    public function installationPageLink()
    {
        return BuildUrl::getUrl('home', 'index',['id'=>$_GET['id']]);
    }

}
