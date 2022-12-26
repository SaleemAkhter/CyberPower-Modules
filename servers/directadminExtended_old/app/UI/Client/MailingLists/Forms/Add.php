<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\InputGroup;

class Add extends BaseForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
                ->setProvider(new Providers\MailingListsCreate());

        $this->addSection($this->getListSection())
            ->loadDataToForm();
    }

    protected function getListSection()
    {
        $list = (new InputGroup('listGroup'))
            ->addInputComponent((new InputGroupElements\Text('name'))->notEmpty()->validEmailUsername())
            ->addInputAddon('@', false, '@')
            ->addInputComponent(new InputGroupElements\Select('domains'))
            ->setDescription('');

        return $list;
    }
}
