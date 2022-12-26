<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Forms;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Providers\ReinstallProvider;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Providers\ServiceActions;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Fields\Text;

class ReinstallForm extends BaseForm implements AdminArea, ClientArea
{

    protected $allowedActions = ['update'];

    public function initContent()
    {
        $this->initIds('reinstallForm');
        $this->setFormType('update');
        $this->setProvider(new ReinstallProvider());
        $this->setConfirmMessage('confirmReinstall');
    }
}
