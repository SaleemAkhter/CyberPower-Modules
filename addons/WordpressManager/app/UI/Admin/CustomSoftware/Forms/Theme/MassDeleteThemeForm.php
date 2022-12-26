<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Forms\Theme;

use ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Providers\Theme\CustomThemeProvider;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;

class MassDeleteThemeForm extends BaseForm implements AdminArea
{
    public function getAllowedActions()
    {
        return ['deleteMass'];
    }

    public function initContent()
    {
        $this->initIds('massDeleteThemeForm');
        $this->setFormType('deleteMass');
        $this->setProvider(new CustomThemeProvider());
        $this->setConfirmMessage('confirmMassDelete');
    }

}
