<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;

use \ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Forms\DeleteAllLoggerForm;

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
