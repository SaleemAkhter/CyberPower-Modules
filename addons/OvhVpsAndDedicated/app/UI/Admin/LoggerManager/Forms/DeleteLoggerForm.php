<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Forms;

use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;

use \ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Providers\LoggerDataProvider;
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
