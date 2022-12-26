<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;

use \ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Forms\DeleteLoggerForm;

/**
 * DOE DeleteLabelModal controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class DeleteLoggerModal extends BaseModal implements AdminArea
{
    protected $id    = 'deleteLoggerModal';
    protected $name  = 'deleteLoggerModal';
    protected $title = 'deleteLoggerModal';

    public function initContent()
    {
        $deleteLabelForm = new DeleteLoggerForm();

        $this->replaceSubmitButtonClasses(['btn btn--danger submitForm']);
        
        $this->addForm($deleteLabelForm);
    }
}
