<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Modals;

use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Forms\AssignClientForm;
use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Forms\AssignProductsForm;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\Alerts;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Helpers\AlertTypesConstants;
/**
 * Class AssignProductsModal
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class AssignProductsModal extends BaseEditModal implements AdminArea
{


    protected $id        = 'AssignProductsModal';
    protected $name      = 'AssignProductsModal';
    protected $title     = 'AssignProductsModal';

    public function initContent()
    {

        $this->addForm(new AssignProductsForm());
    }
}