<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Forms;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class DeleteForm extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'deleteForm';
    protected $name  = 'deleteForm';
    protected $title = 'deleteForm';

    public function initContent()
    {
        $this->setFormType('delete');
        $this->setProvider(new \ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Providers\SnapshotProvider());
        $this->setConfirmMessage('deleteConfirm', ['description' => null]);
        $this->addField(new \ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden('id'));
        $this->addField(new \ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden('description'));
        $this->loadDataToForm();
    }

    public function getAllowedActions()
    {
        return ['delete'];
    }


}
