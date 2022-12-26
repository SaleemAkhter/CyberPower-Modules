<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Forms\Theme;

use ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Providers\Theme\CustomThemeProvider;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;

class DeleteThemeForm extends BaseForm implements AdminArea
{

    public function getAllowedActions()
    {
        return ['delete'];
    }

    public function initContent()
    {
        $this->initIds('massDeleteForm');
        $this->setFormType('delete');
        $this->setProvider(new CustomThemeProvider());
        $this->initFields();
    }

    public function initFields()
    {
        $this->addField(new Hidden('id'));
        $this->addField(new Hidden('name'));
        $this->setConfirmMessage('confirmDelete', ['name' => null]);
        $this->loadDataToForm();
    }
}
