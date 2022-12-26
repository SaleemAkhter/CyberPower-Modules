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

/**
 * Description of CreateForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class InstallationCreateForm extends BaseTabsForm implements ClientArea
{
    protected $formData;
    protected $formFields;
    use main\App\Http\Client\BaseClientController;

    protected $modal;

    /**
     * InstallationCreateForm constructor.
     * @param main\App\UI\Installations\Modals\InstallationCreateModal $modal
     */
    public function __construct(main\App\UI\Installations\Modals\InstallationCreateModal $modal)
    {
        $this->modal    = $modal;

        parent::__construct();
    }

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
        $hostings   = $repository->findEnabledWithProduct($this->request->getSession('uid'), $this->request->get('hostingId', null));
        if(empty($hostings))
        {
            $this->setInternalAlertMessage('allHostingsLimited', ['title' => null]);
            return;
        }

        $section = new Sections\TabSection();
        $section->initIds(__FUNCTION__);
        $section->enableGroupBySectionName();
        $section->setMainContainer($this->mainContainer);
        $section->setName(sl('lang')->T(__FUNCTION__));
        $content     = new Sections\RawSection();
        $rightSide   = new HalfPageSection('rightSide');
        $leftSide    = new HalfPageSection('leftSide');
        $mainSection = new Sections\RawSection('mainSection');
        $content->setMainContainer($this->mainContainer);
        $rightSide->setMainContainer($this->mainContainer);
        $mainSection->setMainContainer($this->mainContainer);
        if (!$this->formData['hostingId'])
        {
            $this->formData['hostingId'] = key($hostings);
        }

        $field = new Fields\Select('hostingId');
        $field->initIds('hostingId');
        $field->setDescription('description');
        $field->setAvailableValues((array)$hostings);
        $field->addHtmlAttribute('bi-event-change', "reloadVueModal");
        $mainSection->addField($field);
        $this->reset()
            ->setHostingId($this->formData['hostingId'])
            ->setUserId($this->request->getSession('uid'));

        //username
        if ($this->getHostingId() && $this->getHosting()->product->isTypeReseller())
        {
            $options = [];
            foreach ($this->subModule()->reseller()->getAccounts() as $account)
            {
                $options[$account['username']] = $account['username'];
            }

            if (empty($options))
            {
                $this->addInternalAlert('no_users_under_reseller_account', main\Core\UI\Helpers\AlertTypesConstants::DANGER);
                $content->addSection($mainSection);
                $section->addSection($content);
                $this->addSection($section);
                $this->modal->removeActionButtonByIndex('baseAcceptButton');
                return;
            }

            $field = new Fields\Select('username');
            $field->notEmpty();

            $field->setAvailableValues($options);
            $mainSection->addField($field);
        }
        //softproto
        $field = new Fields\Select('softproto');
        $field->setAvailableValues((new Decorator())->getProtocols());
        $field->setDefaultValue(3);
        $mainSection->addField($field);
        //softdomain
        $field = new Fields\Text('softdomain');
        $field->setDescription('description');
        $field->notEmpty();
        $mainSection->addField($field);
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
            $mainSection->addField($field);
        }
        else
        {
            if ($total == 1)
            {
                $field = new Fields\Hidden('installationScript');
                $field->setDefaultValue(key($installationScripts));
                $mainSection->addField($field);
            }
        }
        //plugin package
        if ($this->getHostingId() && count((array)$this->getHosting()->productSettings->getPluginPackages()))
        {
            $field = new Fields\Select('pluginPackages');
            $field->enableMultiple();
            $packages = PluginPackage::ofId($this->getHosting()->productSettings->getPluginPackages())
                ->enable()
                ->pluck("name", "id");
            $field->setAvailableValues($packages->all());
            $field->setDescription('description');
            $mainSection->addField($field);
        }
        //site_name
        $field = new Fields\Text('site_name');
        $field->setDescription('description')
            ->setDefaultValue(sl('lang')->T('My WordPress'));
        $field->notEmpty();
        $rightSide->addField($field);
        //language
        $json  = new Json('softaculousLanguages.json', ModuleConstants::getDevConfigDir());
        $field = new Fields\Select('language');
        $field->setAvailableValues((array)$json->get());
        $field->setDescription('description');
        $field->setDefaultValue((new main\App\Helper\LangConveter())->convert());
        $leftSide->addField($field);
        //admin_username
        $rightSide->addField((new Fields\Text('admin_username'))->setDefaultValue('admin')->setDescription('description'));
        //admin_email
        $field = new Fields\Text('admin_email');
        $field->setDescription('description');
        $leftSide->addField($field);
        //admin_pass
        $adminPassword = UtilityHelper::generatePassword(14,'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+=-');
        $mainSection->addField((new Fields\PasswordGenerate('admin_pass'))->setDefaultValue($adminPassword)->setDescription('description'));
        $content->addSection($mainSection);
        $content->addSection($rightSide);
        $content->addSection($leftSide);
        $section->addSection($content);
        $this->addSection($section);

        //Add other tab
        $this->tabOther();
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
        $field = (new Fields\Text('site_desc'))->setDescription('description');
        $content->addField($field);
        //softdirectory
        $field = new Fields\Text('softdirectory');
        $field->setDescription('description');
        $content->addField($field);
        //softdb
        $field = new Fields\Text('softdb');
        $field->notEmpty();
        $field->setDescription('description');
        $content->addField($field);
        //dbprefix
        $field = new Fields\Text('dbprefix');
        $field->setDescription('description');
        $content->addField($field);
        //multisite on off
        $field = (new Fields\Switcher('multisite'))->setDescription('description');
        $content->addField($field);
        //eu_auto_upgrade 
        $field = (new Fields\Select('eu_auto_upgrade'))->setDescription('description');
        $field->setAvailableValues([
            0 => sl("lang")->abtr("Do not auto upgrade"),
            1 => sl("lang")->abtr("Upgrade to any latest version available (Major as well as Minor)"),
            2 => sl("lang")->abtr("Upgrade to Minor versions only"),
        ]);
        $content->addField($field);
        //auto_upgrade_plugins
        $field = (new Fields\Switcher('auto_upgrade_plugins'))->setDescription('description');
        $content->addField($field);
        //auto_upgrade_themes 
        $field = (new Fields\Switcher('auto_upgrade_themes'))->setDescription('description');
        $content->addField($field);
        $section->addSection($content);
        $this->addSection($section);
    }

}
