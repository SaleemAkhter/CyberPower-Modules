<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ApacheHandlers\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ApacheHandlers\Providers;

class Add extends BaseForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
                ->setProvider(new Providers\ApacheHandlers());

        $this->loadFields();
        $this->loadDataToForm();
    }

    protected function loadFields()
    {
        $domain =   new Fields\Select('domain');
        $handler    = (new Fields\Text('handler'))->notEmpty();
        $extension  = (new Fields\Text('extension'))->notEmpty();
        $extension->setDescription('description');

        $this->addField($domain)
            ->addField($handler)
            ->addField($extension);

        return $this;
    }
}
