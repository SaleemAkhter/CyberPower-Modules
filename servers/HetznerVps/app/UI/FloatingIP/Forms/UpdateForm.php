<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\FloatingIP\Forms;

use ModulesGarden\Servers\HetznerVps\App\Helpers\Validators\DomainValidator;
use ModulesGarden\Servers\HetznerVps\App\UI\FloatingIP\Providers\FloatingIPProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;

class UpdateForm extends BaseForm implements ClientArea, AdminArea
{

    protected $id = 'floatingIPsEditForm';
    protected $name = 'floatingIPsEditForm';
    protected $title = 'floatingIPsEditForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new FloatingIPProvider());

        $id = (new Hidden('id'))->initIds('id');
        $dns = (new Text('dns'))
            ->notEmpty()
            ->initIds('dns');

        $this->addField($dns)
            ->addField($id);

        $this->loadDataToForm();
    }
}
