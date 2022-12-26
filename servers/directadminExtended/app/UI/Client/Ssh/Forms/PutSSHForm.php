<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\PasswordGenerateExtended;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Providers\SshProvider;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Checkbox;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Password;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;


class PutSSHForm extends BaseForm implements ClientArea
{
    protected $id    = 'putForm';
    protected $name  = 'putForm';
    protected $title = 'putForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::PUT)
            ->setProvider(new SshProvider());

        $sshKey = (new Sections\InputGroup('sshKey'))
            ->addTextField('sshKey');

        $sshKey->getField('sshKey')->setPlaceholder('e.g. (options) ssh-rsa AAAABBBBCCCC123456789 ...');

        $this->addSection($sshKey);
        $this->loadDataToForm();
    }
}
