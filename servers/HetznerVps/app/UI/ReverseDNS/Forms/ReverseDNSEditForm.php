<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Forms;


use ModulesGarden\Servers\HetznerVps\App\Helpers\Validators\DomainValidator;
use ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Providers\ReverseDNSProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;

class ReverseDNSEditForm extends BaseForm implements ClientArea, AdminArea
{
    protected $id               = 'reverseDNSEditForm';
    protected $name             = 'reverseDNSEditForm';
    protected $title            = 'reverseDNSEditForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new ReverseDNSProvider());

        $ip = (new Hidden('ip'))->initIds('ip');
        $dns_ptr = (new Text('dns_ptr'))
            ->initIds('dns_ptr')
            ->addValidator(new DomainValidator());

        $this->addField($ip)
            ->addField($dns_ptr);

        $this->loadDataToForm();
    }
}