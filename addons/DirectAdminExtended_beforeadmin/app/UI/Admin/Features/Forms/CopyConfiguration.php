<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Forms;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Providers;

class CopyConfiguration extends BaseForm implements AdminArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
                ->setProvider(new Providers\FeaturesProvider());

        $selectedProductHidden = new Hidden('selectedProduct');
        $product               = new Select('fromProduct');

        $this->addField($selectedProductHidden)
                ->addField($product)
                ->loadDataToForm();
    }
}
