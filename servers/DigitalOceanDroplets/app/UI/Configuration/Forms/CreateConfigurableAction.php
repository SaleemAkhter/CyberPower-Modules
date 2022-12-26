<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Providers\ConfigurableOptionManage;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class CreateConfigurableAction extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'createConfigurableAction';
    protected $name  = 'createConfigurableAction';
    protected $title = 'createConfigurableAction';

    public function getClass()
    {
        
    }

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new ConfigurableOptionManage());
        $dataProvider = $this->getFormData();
        $this->loadLang();

        $this->lang->addReplacementConstant('configurableOptionsNameUrl', '<a style="color: #31708f; text-decoration: underline;" href="https://docs.whmcs.com/Addons_and_Configurable_Options" target="blank">here</a>');

        $this->addInternalAllert('configurableOptionsNameInfo', AlertTypesConstants::INFO);

        if (is_array($dataProvider['fields']))
        {
            foreach ($dataProvider['fields'] as $key => $name)
            {
                $this->addField((new \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Switcher($key))->setDefaultValue('on')->setRawTitle($key.'|'.$name));
            }
        }
        $this->loadDataToForm();
    }

}
