<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Providers;
use function \ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;

class Delete extends BaseForm implements ClientArea
{
    protected $id    = 'deleteForm';
    protected $name  = 'deleteForm';
    protected $title = 'deleteForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
                ->setProvider(new Providers\Ssl())
                ->setConfirmMessage('confirmDeleteCertificate');

        $name   = new Fields\Hidden('name');
        $domain = (new Fields\Hidden('domain'))->setDefaultValue(sl('request')->get('index'));

        $this->addField($name)
                ->addField($domain)
                ->loadDataToForm();
    }
}
