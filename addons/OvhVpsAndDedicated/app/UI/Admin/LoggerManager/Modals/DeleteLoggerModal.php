<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Modals;

use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;

use \ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Forms\DeleteLoggerForm;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Modals\ModalConfirmDanger;

/**
 * DOE DeleteLabelModal controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class DeleteLoggerModal extends ModalConfirmDanger implements AdminArea
{
    protected $id    = 'deleteLoggerModal';
    protected $name  = 'deleteLoggerModal';
    protected $title = 'deleteLoggerModal';

    public function initContent()
    {
        $deleteLabelForm = new DeleteLoggerForm();

        $this->addForm($deleteLabelForm);
    }
}
