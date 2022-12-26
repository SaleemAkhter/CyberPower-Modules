<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator\Email;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Providers;

class AddSubNormal extends BaseForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
                ->setProvider(new Providers\MailingListsSub());

        $this->loadFields()
            ->loadDataToForm();
    }

    protected function loadFields()
    {
        $email = (new Fields\Text('email'))->addValidator(new Email())->notEmpty();
        $type = new Fields\Hidden('type');

        $this->addField($email)
            ->addField($type);

        return $this;
    }
}
