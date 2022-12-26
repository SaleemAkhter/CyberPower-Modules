<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Forms\Plugin;

use ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Providers\Plugin\CustomPluginProvider;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;

class MassDeletePluginForm extends BaseForm implements AdminArea
{
    public function getAllowedActions()
    {
        return ['deleteMass'];
    }

    public function initContent()
    {
        $this->initIds('massDeletePluginForm');
        $this->setFormType('deleteMass');
        $this->setProvider(new CustomPluginProvider());
        $this->setConfirmMessage('confirmMassDelete');
    }

}
