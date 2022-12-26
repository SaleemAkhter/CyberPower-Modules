<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamFilter\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamFilter\Providers;

class Add extends BaseForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new Providers\SpamFilterCreate());

        $this->loadFields()
            ->loadDataToForm();
    }

    protected function loadFields()
    {
        $domain = new Fields\Select('domains');
        $filter = new Fields\Select('filter');
        $value  = (new Fields\Text('value'))->setDescription('description')->notEmpty();

        $this->addField($domain)
            ->addField($filter)
            ->addField($value);

        return $this;
    }
}
