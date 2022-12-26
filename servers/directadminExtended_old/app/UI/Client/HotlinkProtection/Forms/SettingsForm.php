<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\HotlinkProtection\Forms;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\HotlinkProtection\Providers\HotlinkProtectionProvider;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Switcher;

class SettingsForm extends BaseForm implements ClientArea
{
    protected $id    = 'settingsForm';
    protected $name  = 'editForm';
    protected $title = 'editForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->addDefaultActions(FormConstants::READ)
            ->setProvider(new HotlinkProtectionProvider())
            ->addField((new Switcher('enable')))
            ->addField((new Text('fileTypes')))
            ->addField((new Switcher('allowBlankReferer')))
            ->addField((new Switcher('redirect')))
            ->addField((new Text('redirectUrl')))
            ->addField((new Hidden('domain')))
            ->loadDataToForm();
    }
}
