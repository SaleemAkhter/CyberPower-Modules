<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Forms\MassAction;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Providers;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;

class DeleteSub extends BaseForm implements ClientArea
{
    protected $id    = 'massDeleteSubForm';
    protected $name  = 'massDeleteSubForm';
    protected $title = 'massDeleteSubForm';

    public function getDefaultActions()
    {
        return ['deleteMany'];
    }

    public function initContent()
    {
        $this->setFormType('deleteMany')
            ->setProvider(new Providers\MailingListsSub())
            ->setConfirmMessage('deleteMany');
    }
}