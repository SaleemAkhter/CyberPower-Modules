<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Providers;

class StorageSettings extends BaseForm implements ClientArea
{
    protected $id    = 'storageSettingsForm';
    protected $name  = 'storageSettingsForm';
    protected $title = 'storageSettingsForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
                ->setProvider(new Providers\StorageSettings());

        $host           = (new Fields\Text('host'))->notEmpty();
        $directory      = new Fields\Text('directory');
        $username       = (new Fields\Text('username'))->notEmpty();
        $password       = new Fields\Text('password');
        $passiveMode    = new Fields\Switcher('passiveMode');
        
        $this->addField($host)
                ->addField($directory)
                ->addField($username)
                ->addField($password)
                ->addField($passiveMode)
                ->loadDataToForm();
    }
}
