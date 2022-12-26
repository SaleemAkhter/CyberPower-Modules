<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\FileEditor\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseTabsForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\PasswordGenerateExtended;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\FileEditor\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;


class Auth extends BaseTabsForm implements ClientArea
{
    protected $id    = 'authForm';
    protected $name  = 'authForm';
    protected $title = 'authForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new Providers\File());
        $this->addField((new Fields\Hidden("filecontent")));
        $this->addField((new Fields\Hidden("filename")));
        $this->addField((new Fields\Password('rootpassword')));
    }

}
