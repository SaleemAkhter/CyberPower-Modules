<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Pages;

use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Fields\ActionSelect;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\BaseStandaloneFormExtSections;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BoxSection;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Providers\ConfigurationProvider;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Helpers\FeaturesHelper;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Fields\ActionSwitcher;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Sections\BoxDoubleSection;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Sections\NoWidgetSection;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Fields\RenderSelect;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Select;

class Configuration extends BaseStandaloneFormExtSections implements AdminArea
{
    protected $id    = 'configuration';
    protected $name  = 'configuration';
    protected $title = 'configurationPageTitle';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
                ->setProvider(new ConfigurationProvider())
                ->loadSection()
                ->loadDataToForm();
    }

    protected function loadSection()
    {
        $this->featuresSection()
                ->applicationsSection();

        return $this;
    }

    protected function featuresSection()
    {
        $section             = new BoxSection();
        $section->initIds('featuresSection')->disabledViewHeader();
        $features = FeaturesHelper::getFeaturesNames();

        foreach ($features as $fName => $fOptions)
        {
            $name = FeaturesHelper::getName($fName);

            $mainSwitch = new ActionSwitcher('featureSection');
            $mainSwitch
                        ->addHtmlAttribute('onchange', "checkSection('',[],event)")
                        ->setMainContainer($this->mainContainer)
                        ->initIds($name)
                        ->setRawTitle(' ')
                        ->setName($mainSwitch->getName())
                        ->setId($mainSwitch->getId());



            $this->fields[$mainSwitch->getId()] = $mainSwitch;

            $column = new \ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Sections\FeatureSection();
            $column->initIds($name)
                    ->setTooltip($mainSwitch)
                    ->setTitle($name);

            foreach ($fOptions as $opt)
            {
                $name   = FeaturesHelper::getName($opt, $fName);
                $switch = new ActionSwitcher();
                $switch
                    ->addHtmlAttribute('onchange', "checkOptionUnderSection(event)")
                        ->setMainContainer($this->mainContainer)
                        ->initIds($opt)
                        ->setRawTitle($name);
                $column->addField($switch);
            }

            $section->addSection($column);
        }
        $this->addSection($section);

        return $this;
    }

    protected function applicationsSection()
    {
        $section = new BoxDoubleSection();
        $section->initIds('applicationsSection');

        $halfLeft = new NoWidgetSection();
        $halfLeft->initIds('leftSide')
                ->setTitle('applicationsSettings');

        $halfRight = new NoWidgetSection();
        $halfRight->initIds('rightSide')
            ->setTitle('otherSettings');

        $installApp = new Switcher('apps');
        $installApp
                ->addHtmlAttribute('onchange', 'changeFieldStatus({0:\'apps_installer_type\',1:\'apps_app_name\'},event, false)')
                ->addHtmlAttribute('@change', 'selectChangeAction($event)')
                ->setDescription('installAppDescription');
        
        $autoInstaller = new Select();
        $autoInstaller->initIds('apps_installer_type')
                    ->setHtmlAttributes([
                        '@change'  => 'selectChangeAction($event)',
                    ])
                    ->setDescription('autoinstallerDescription');



        $useConfigOpt = new Switcher();
        $useConfigOpt->initIds('apps_order_assign')
                    ->addHtmlAttribute('@change', 'selectChangeAction($event)');

        $installTab = new Switcher('apps_installation');
        $backupTab  = new Switcher('apps_backups');

        $appsConfigOpt = new RenderSelect();
        $appsConfigOpt->initIds('apps_app_name')
                ->addReloadOnChangeField('apps')
                ->addReloadOnChangeField('apps_installer_type')
                ->addReloadOnChangeField('apps_order_assign')
                ->setDescription('appNameDescription');

        $language = new Text();
        $language->initIds('apps_lang')
                ->setDescription('languageDescription')
                ->setPlaceholder('en');
        
        $autoUpdateBackups = new Switcher('auto_apps_backups');
        $autoUpdateBackups->setDescription('autoUpdateBackupsDesc');

        $defaultOnOrder = new Switcher();
        $defaultOnOrder->initIds('auto_apps_backups_default')
                ->setDescription('defaultOnOrderDescription');

        $webmailType    = new Select('webmail_type');
        $showServerIp   = new Switcher('show_ip');
        $encryptButton  = new Switcher('ssl_allow_encrypt');


        $halfLeft->addField($installApp)
                ->addField($autoInstaller)
                ->addField($useConfigOpt)
                ->addField($appsConfigOpt)
                ->addField($installTab)
                ->addField($backupTab)
                ->addField($language)
                ->addField($autoUpdateBackups)
                ->addField($defaultOnOrder);

        $halfRight->addField($webmailType)
            ->addField($showServerIp)
            ->addField($encryptButton);

        $section->addSection($halfLeft)
            ->addSection($halfRight);

        $this->addSection($section);
    }

    protected function disableFields()
    {
        $appSectionFields = $this->getSections()['applicationsSection']->getSections()['leftSide']->getFields();
        if ($appSectionFields['install_app']->getValue() != 'on')
        {
            foreach ($appSectionFields as $field)
            {
                if ($field->getId() == 'install_app')
                {
                    continue;
                }
                $field->disableField();
            }
        }
    }
}
