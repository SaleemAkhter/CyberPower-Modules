<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;

use \ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Providers\LoggerDataProvider;
/**
 * DOE DeleteLabelForm controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class DeleteLoggerForm extends BaseForm implements AdminArea
{
    protected $id    = 'deleteLoggerForm';
    protected $name  = 'deleteLoggerForm';
    protected $title = 'deleteLoggerForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE);
        $this->dataProvider = new LoggerDataProvider();

        $this->setConfirmMessage('confirmLabelRemoval');
        
        $field = new Hidden();
        $field->setId('id');
        $field->setName('id');
        $this->addField($field);

        $this->loadDataToForm();
    }
}
