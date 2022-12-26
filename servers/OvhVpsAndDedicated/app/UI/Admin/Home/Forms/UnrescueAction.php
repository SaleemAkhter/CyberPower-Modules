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
class UnrescueAction extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'unrescueActionForm';
    protected $name  = 'unrescueActionForm';
    protected $title = 'unrescueActionForm';
    protected $allowedActions = [
        'rescue', 'unrescue'
    ];

    public function initContent()
    {
        $this->setFormType('unrescue');
        $this->setProvider(new Rescue());
        $this->setConfirmMessage('confirmUnrescue');
        $this->loadDataToForm();
    }

}
