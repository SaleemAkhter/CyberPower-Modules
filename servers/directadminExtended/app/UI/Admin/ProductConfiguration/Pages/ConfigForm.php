<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Providers\Config;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Sections\ConfigurableOptionsFields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Sections\ConfigurationFields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Sections\CustomFields;
use ModulesGarden\Servers\DirectAdminExtended\Core\ModuleConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormIntegration;

/**
 * Created by PhpStorm.
 * User: Mateusz
 * Date: 01.04.2019
 * Time: 19:10
 */




class ConfigForm extends FormIntegration implements AdminArea
{
    protected $id = 'configForm';
    protected $name = 'configForm';
    protected $title = 'configForm';


    public function initContent()
    {
        $this->checkAddonActive();
        $this->checkPermission();

        $provider = new Config();
        $this->setProvider($provider);

        $configurationFields = new ConfigurationFields();
        $this->addSection($configurationFields);

         $customFields =  new CustomFields();
        $this->addSection($customFields);

         $configurableOptionsFields = new ConfigurableOptionsFields();
        $this->addSection($configurableOptionsFields); 

        $this->loadDataToForm();
    }



    public function checkAddonActive()
    {
        \ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DirectAdminExtendedAddon::isActive(true);
    }

    public function checkPermission()
    {
        $path = ModuleConstants::getModuleRootDir() . DS . 'storage';

        if (is_writable($path) === false || is_readable($path) === false) {
            throw new \Exception(ServiceLocator::call('lang')
                ->addReplacementConstant('storage_path', ModuleConstants::getFullPath('storage'))
                ->absoluteT('permissionsStorage'));
        }
    }
}
