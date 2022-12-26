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
class DeleteMassForm extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'deleteMassForm';
    protected $name  = 'deleteMassForm';
    protected $title = 'deleteMassForm';

    public function initContent()
    {
        $this->setFormType('deleteMass');
        $this->setProvider(new \ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Providers\SnapshotProvider());
        $this->setConfirmMessage('deleteMassConfirm');
        $this->loadDataToForm();
    }

    public function getAllowedActions()
    {
        return ['deleteMass'];
    }


}
