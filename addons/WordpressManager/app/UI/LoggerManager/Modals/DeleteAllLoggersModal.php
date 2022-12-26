<?php

namespace ModulesGarden\WordpressManager\App\UI\LoggerManager\Modals;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\BaseModal;
use \ModulesGarden\WordpressManager\App\UI\LoggerManager\Forms\DeleteAllLoggerForm;

/**
 * DOE AddCategoryModal controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class DeleteAllLoggersModal extends BaseModal implements AdminArea
{
    protected $id    = 'deleteAllLoggersModal';
    protected $name  = 'deleteAllLoggersModal';
    protected $title = 'deleteAllLoggersModal';

    public function initContent()
    {
        $this->addForm(new DeleteAllLoggerForm());
    }
}
