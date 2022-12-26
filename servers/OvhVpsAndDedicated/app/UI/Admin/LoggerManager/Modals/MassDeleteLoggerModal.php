<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;

use \ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Forms\DeleteLoggerForm;

/**
 * DOE DeleteTldModal controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class MassDeleteLoggerModal extends BaseModal implements AdminArea
{
    protected $id    = 'massDeleteLoggerModal';
    protected $name  = 'massDeleteLoggerModal';
    protected $title = 'massDeleteLoggerModal';

    public function initContent()
    {
        $this->replaceSubmitButtonClasses(['btn btn--danger submitForm']);
        
        $this->addForm(new DeleteLoggerForm());
    }
}
