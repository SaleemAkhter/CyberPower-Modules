<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Pages;

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

class Settings extends BaseStandaloneFormExtSections implements AdminArea
{
    protected $id    = 'configurationPage';
    protected $name  = 'configurationPage';
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
        $section->initIds('featuresSection')
            ->setTitle(null);
        $features = FeaturesHelper::getFeaturesNames();

        foreach ($features as $fName => $fOptions)
        {
            $name = FeaturesHelper::getName($fName);

            $mainSwitch = new ActionSwitcher('featureSection');
            $mainSwitch->setCustomActionName('checkSection')
                    ->setMainContainer($this->mainContainer)
                    ->initIds($name)
                    ->setRawTitle('')
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
                $switch->setCustomActionName('checkOptionUnderSection')
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

        $halfRight = new NoWidgetSection('otherSection');

        $installApp = new ActionSwitcher();
        $installApp->setCustomActionName('installAppConfigurtaion')
                ->setMainContainer($this->mainContainer)
                ->initIds('client_install_app')
                ->setDescription('installAppDescription');
        
        $autoInstaller = new \ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Fields\ActionSelect();
        $autoInstaller->initIds('autoinstaller')
                ->setHtmlAttributes([
                    'href'     => 'javascript:;',
                    'onchange' => '\'autoInstallerOptions(event)\''
                    ])
                ->setDescription('autoinstallerDescription');

        $installOnCreate = new Switcher('autoinstall_on_create');

        $useConfigOpt = new Switcher();
        $useConfigOpt->initIds('apps_order_assign');

        $appsConfigOpt = new RenderSelect();
        $appsConfigOpt->initIds('app_name')
                ->addRenderAgainByField($autoInstaller->getId())
                ->addRenderAgainByField($useConfigOpt->getId())
                ->setDescription('appNameDescription');

        $language = new Text();
        $language->initIds('lang')
                ->setDescription('languageDescription')
                ->setPlaceholder('en');
        
        $autoUpdateBackups = new Switcher('auto_apps_backups');
        $autoUpdateBackups->setDescription('autoUpdateBackupsDesc');

        $defaultOnOrder = new Switcher();
        $defaultOnOrder->initIds('auto_apps_backups_default')
                ->setDescription('defaultOnOrderDescription');

        $halfLeft->addField($installApp)
                ->addField($autoInstaller)
                ->addField($installOnCreate)
                ->addField($useConfigOpt)
                ->addField($appsConfigOpt)
                ->addField($language)
                ->addField($autoUpdateBackups)
                ->addField($defaultOnOrder);

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
