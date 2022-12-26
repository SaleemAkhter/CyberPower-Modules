<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Providers;

class Upload extends BaseForm implements ClientArea
{
    protected $id    = 'uploadForm';
    protected $name  = 'uploadForm';
    protected $title = 'uploadForm';

    public function getDefaultActions()
    {
        return ['upload'];
    }

    public function initContent()
    {
        $this->setFormType('upload')
                ->setProvider(new Providers\SslCreate());

        $domain        = (new Fields\Select('domains'))->notEmpty();
        $privateKey    = (new Fields\Textarea('privateKey'))->notEmpty();
        $certificate   = (new Fields\Textarea('certificate'))->notEmpty();

        $this->addField($domain)
                ->addField($privateKey)
                ->addField($certificate)
                ->loadDataToForm();
    }
}
