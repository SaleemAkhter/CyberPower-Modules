<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Helpers\InstallerNumber;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Fields\Information;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Select;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BaseSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\TabSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ApplicationInstaller;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Helpers\FieldInstallerBuilder;
use function \ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;

class InstallNewApp extends BaseForm implements ClientArea
{
    use ProductsFeatureComponent,
        DirectAdminAPIComponent;

    protected $id    = 'installNewAppForm';
    protected $name  = 'installNewAppForm';
    protected $title = 'installNewAppForm';

    protected $appInstaller;
    protected $fieldBuilder;
    protected $installerName;

    public function initContent()
    {
        $this->setProvider(new Providers\ApplicationsCreate())
            ->setFormType(FormConstants::CREATE)
            ->loadApiInstance();

        $indexParams    = sl('request')->get('index');
        $explodeParams  = explode(',', $indexParams);
        $sid            = $explodeParams[0];
        $version        = $explodeParams[1];
        $script         = $this->appInstaller->getScriptBySid($sid);
        if(!$script)
        {
            return;
        }

        $fields          = $this->appInstaller->getInstallationFields($sid);
        $domain          = (new Select('domain'))
            ->setDescription('domainDescription');

        $action = new Hidden();
        $action->initIds('action')
            ->setDefaultValue('installApp');

        $sidHidden = (new Hidden('sid'))
            ->setDefaultValue($sid);

        $this->addField($sidHidden)
            ->addField($action);

        $detailsTab         = (new TabSection('detailsTab'))->setTitle('detailsTabTitle');
        $detailsTabSection  = (new FormGroupSection('detailsSection'))
            ->addField($domain);

        $loginTab           = (new TabSection('loginTab'))->setTitle('loginTabTitle');
        $loginTabSection    = new FormGroupSection('loginSection');

        $installationFields = $fields->getFields();

        $this->fieldBuilder->setAdminEmail($this->getWhmcsParamByKey('model')->client->email);

        foreach ($installationFields as $field)
        {
            if ($this->isFeatureEnabled('auto_apps_backups') === false && $this->installerName == InstallerNumber::INSTALLATRON && $field->getName() == 'autoup_backup')
            {
                continue;
            }
            $field = $this->fieldBuilder->buildField($field);
            if(strpos($field->getName(), 'admin') !== false)
            {
                $loginTabSection->addField($field);
                continue;
            }
            $dbFieldsName = ['softdb', 'db_user', 'db_pass', 'dbprefix'];
            if(in_array($field->getName(), $dbFieldsName) && $this->installerName == InstallerNumber::INSTALLATRON)
            {
                $field->setDescription('description');

            }

            $detailsTabSection->addField($field);
        }

        if(empty($loginTabSection->getFields())){
            $loginTabSection->addField(new Information());
        }

        $detailsTab->addSection($detailsTabSection);
        $loginTab->addSection($loginTabSection);

        $tabs   = [];
        $tabs[] = $detailsTab;
        $tabs[] = $loginTab;
        if ($this->installerName == InstallerNumber::SOFTACULOUS)
        {
            $tabs[] = $this->getOtherTab();
        }

        foreach($tabs as $tab)
        {
            $this->addSection($tab);
        }

        $this->loadDataToForm();
    }

    protected function getOtherTab()
    {
        $otherTab           = (new TabSection('otherTab'))->setTitle('otherTabTitle');
        $otherTabSection    = new FormGroupSection('otherSection');
        $otherTabSection->addField(new Switcher('auto_upgrade_themes'))
            ->addField(new Select('auto_backup'))
            ->addField(new Select('auto_backup_rotation'));

        $otherTab->addSection($otherTabSection);

        return $otherTab;
    }

    private function loadApiInstance()
    {
        $appInstallHelper    = new ApplicationInstaller($this->loadRequiredParams());
        $this->fieldBuilder  = new FieldInstallerBuilder();
        $this->installerName = $appInstallHelper->getInstallerName();
        $this->appInstaller  = $appInstallHelper->getInstaller();
    }
}
