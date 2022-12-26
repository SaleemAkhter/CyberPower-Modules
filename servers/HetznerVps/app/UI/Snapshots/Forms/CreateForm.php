<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Forms;

use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Providers\CreateProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Textarea;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class CreateForm extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'createForm';
    protected $name  = 'createForm';
    protected $title = 'createForm';

    public function initContent()
    {
        $this->setFormType('create');
        $this->setProvider(new CreateProvider());
        $this->setConfirmMessage('createConfirm');
        $this->addField((new Textarea('description'))->notEmpty());
        $this->loadDataToForm();
    }

    public function getAllowedActions()
    {
        return ['create'];
    }


}
