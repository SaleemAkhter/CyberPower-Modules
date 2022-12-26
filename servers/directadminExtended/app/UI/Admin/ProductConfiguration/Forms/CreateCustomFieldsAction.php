<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Providers\CustomFieldsProvider;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class CreateCustomFieldsAction extends BaseForm implements AdminArea
{

    protected $id    = 'createConfigurableAction';
    protected $name  = 'createConfigurableAction';
    protected $title = 'createConfigurableAction';

    public function getClass()
    {
        
    }

    public function initContent()
    {
        $provider   = new CustomFieldsProvider();

        $this->setFormType(FormConstants::CREATE);
        $this->setProvider($provider);

        foreach ($provider->getCustomFields() as $key => $name) {
            $this->addField((new \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Switcher($name['name']))->setDefaultValue('on')->setRawTitle($name['title']));
        }
        $this->loadDataToForm();
    }

}
