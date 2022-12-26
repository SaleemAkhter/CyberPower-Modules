<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Basics\BaseProductAppForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Providers\ConfigurableOptionManage;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
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
