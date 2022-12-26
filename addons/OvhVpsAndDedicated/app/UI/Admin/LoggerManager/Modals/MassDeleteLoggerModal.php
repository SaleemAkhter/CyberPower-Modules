<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Modals;

use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;

use \ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Forms\DeleteLoggerForm;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Modals\ModalConfirmDanger;

/**
 * DOE DeleteTldModal controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class MassDeleteLoggerModal extends ModalConfirmDanger implements AdminArea
{
    protected $id    = 'massDeleteLoggerModal';
    protected $name  = 'massDeleteLoggerModal';
    protected $title = 'massDeleteLoggerModal';

    public function initContent()
    {
        $this->addForm(new DeleteLoggerForm());
    }
}
