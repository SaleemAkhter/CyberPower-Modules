<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Forms;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Textarea;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class UpdateForm extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'updateForm';
    protected $name  = 'updateForm';
    protected $title = 'updateForm';

    public function initContent()
    {
        $this->setFormType('update');
        $this->setProvider(new \ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Providers\SnapshotProvider());
        $this->addField(new \ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden('id'));
        $this->addField((new Textarea('description'))->notEmpty());
        $this->loadDataToForm();
    }

    public function getAllowedActions()
    {
        return ['update'];
    }


}
