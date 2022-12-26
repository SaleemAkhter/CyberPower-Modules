<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Forms;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Password;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Providers;

class Activate extends BaseForm implements ClientArea
{
    protected $id    = 'activateForm';
    protected $name  = 'activateForm';
    protected $title = 'activateForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
                ->setProvider(new Providers\PluginActivate())
                ->setConfirmMessage('confirmPluginActivate');

        $plugin = new Hidden('plugin');

        $this->addField($plugin)
            ->addField(new Password('password'))
            ->loadDataToForm();
    }
}
