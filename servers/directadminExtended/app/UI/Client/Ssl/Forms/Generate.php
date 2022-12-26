<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Providers;

class Generate extends BaseForm implements ClientArea
{
    protected $id    = 'generateForm';
    protected $name  = 'generateForm';
    protected $title = 'generateForm';
    protected $pvtSection;

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
                ->setProvider(new Providers\SslCreate());

        $domains    = new Fields\Select('domains');
        $code       = new Fields\Text('code');
        $state      = new Fields\Text('state');
        $city       = new Fields\Text('city');
        $company    = new Fields\Text('company');
        $division   = new Fields\Text('division');
        $name       = new Fields\Text('name');
        $email      = new Fields\Text('email');
        $size       = new Fields\Select('size');

        $this->addField($domains)
            ->addField($code)
            ->addField($state)
            ->addField($city)
            ->addField($company)
            ->addField($division)
            ->addField($name)
            ->addField($email)
            ->addField($size)
            ->loadDataToForm();
    }

}
