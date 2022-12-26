<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamFilter\Forms\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamFilter\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;


class Delete extends BaseForm implements ClientArea
{
    protected $id    = 'massDeleteForm';
    protected $name  = 'massDeleteForm';
    protected $title = 'massDeleteForm';

    public function getDefaultActions()
    {
        return ['deleteMany'];
    }

    public function initContent()
    {
        $id     = new Hidden('id');
        $domain = new Hidden('domain');

        $this->addField($id)
            ->addField($domain);

        $this->setFormType('deleteMany')
            ->setProvider(new Providers\SpamFilter())
            ->setConfirmMessage('deleteMany');
    }
}