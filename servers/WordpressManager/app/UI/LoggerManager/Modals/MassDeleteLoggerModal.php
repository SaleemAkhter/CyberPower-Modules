<?php

namespace ModulesGarden\WordpressManager\App\UI\LoggerManager\Modals;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\ModalConfirmDanger;
use \ModulesGarden\WordpressManager\App\UI\LoggerManager\Forms\DeleteLoggerForm;

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
