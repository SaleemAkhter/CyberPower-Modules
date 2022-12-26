<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Dashboard\Forms;

use ModulesGarden\DirectAdminExtended\Core\UI\Traits\Alerts;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Dashboard\Providers;
use ModulesGarden\DirectAdminExtended\Core\UI\Helpers\AlertTypesConstants;

class Upgrade extends BaseForm implements AdminArea
{
    use Alerts;

    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
                ->addInternalAlert('upgradeDescription', AlertTypesConstants::INFO)
                ->setProvider(new Providers\Upgrade());

        $selectedProductHidden  = new Hidden('selectedProduct');
        $serverGroup            = new Select('serverGroup');

        $this->addField($selectedProductHidden)
                ->addField($serverGroup)
                ->loadDataToForm();
    }
}
