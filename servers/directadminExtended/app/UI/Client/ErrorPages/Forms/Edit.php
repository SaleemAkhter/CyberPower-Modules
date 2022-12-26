<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ErrorPages\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ErrorPages\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;

class Edit extends BaseForm implements ClientArea
{
    protected $id    = 'editForm';
    protected $name  = 'editForm';
    protected $title = 'editForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
                ->setProvider(new Providers\ErrorPages());

        $this->loadFields()
            ->loadDataToForm();
    }

    protected function loadFields()
    {
        $errorPage = (new Fields\Text('errorPage'))->disableField();
        $errorHidd = new Fields\Hidden('errorPageHidden');
        $body      = (new Fields\Textarea('body'))->setRows(10);

        $this->addField($errorPage)
            ->addField($errorHidd)
            ->addField($body);

        return $this;
    }
}
