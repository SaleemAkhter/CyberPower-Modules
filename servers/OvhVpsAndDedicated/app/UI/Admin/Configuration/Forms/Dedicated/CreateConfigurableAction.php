<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Providers\Dedicated\ConfigurableDedicatedOptionManage;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\CreateConfigurableAction as DedicatedCreateConfigurableAction;

/**
 * Description of RebuildVM
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class CreateConfigurableAction extends DedicatedCreateConfigurableAction implements ClientArea, AdminArea
{
    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new ConfigurableDedicatedOptionManage());
        $this->loadDataToForm();

        $dataProvider = $this->getFormData();

        if (is_array($dataProvider['fields']))
        {
            foreach ($dataProvider['fields'] as $key => $name)
            {
                $this->addField((new \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Switcher($key))->setDefaultValue('on')->setRawTitle($name));
            }
        }
    }
}
