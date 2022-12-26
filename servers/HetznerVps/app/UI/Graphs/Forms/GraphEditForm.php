<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Graphs\Forms;

use ModulesGarden\Servers\HetznerVps\App\Helpers\Validators\DomainValidator;
use ModulesGarden\Servers\HetznerVps\App\UI\Graphs\Providers\GraphProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;

class GraphEditForm extends BaseForm implements ClientArea, AdminArea
{
    protected $id               = 'reverseDNSEditForm';
    protected $name             = 'reverseDNSEditForm';
    protected $title            = 'reverseDNSEditForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new GraphProvider());

//        $ip = (new Hidden('ip'))->initIds('ip');
//        $dns_ptr = (new Text('dns_ptr'))
//            ->initIds('dns_ptr')
//            ->addValidator(new DomainValidator());
//
//        $this->addField($ip)
//            ->addField($dns_ptr);

        $this->loadDataToForm();
    }
}