<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Providers\Rescue;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class RescueAction extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'rescueActionForm';
    protected $name  = 'rescueActionForm';
    protected $title = 'rescueActionForm';
    protected $allowedActions = [
        'rescue', 'unrescue'
    ];

    public function initContent()
    {
        $this->setFormType('rescue');
        $this->setProvider(new Rescue());
        $this->setConfirmMessage('confirmRescue');
        $this->loadDataToForm();
    }

}
