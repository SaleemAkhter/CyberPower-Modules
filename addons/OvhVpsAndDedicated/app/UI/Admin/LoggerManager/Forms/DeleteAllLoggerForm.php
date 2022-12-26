<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Forms;

use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Hidden;

use \ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Providers\LoggerDataProvider;
/**
 * DOE DeleteLabelForm controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class DeleteAllLoggerForm extends BaseForm implements AdminArea
{
    protected $id    = 'deleteAllLoggerForm';
    protected $name  = 'deleteAllLoggerForm';
    protected $title = 'deleteAllLoggerForm';

    public function initContent()
    {
        $this->setFormType('deleteall');
        $this->dataProvider = new LoggerDataProvider();

        $this->setConfirmMessage('confirmLabelRemoval');
        
        $field = new Hidden();
        $field->setId('id');
        $field->setName('id');
        $this->addField($field);

        $this->loadDataToForm();
    }
    
    protected function getDefaultActions()
    {
        return array_merge(parent::getDefaultActions(), ['deleteall']);
    }
}
