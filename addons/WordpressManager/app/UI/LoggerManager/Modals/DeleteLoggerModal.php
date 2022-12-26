<?php

namespace ModulesGarden\WordpressManager\App\UI\LoggerManager\Modals;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ModalConfirmDanger;
use \ModulesGarden\WordpressManager\App\UI\LoggerManager\Forms\DeleteLoggerForm;

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
