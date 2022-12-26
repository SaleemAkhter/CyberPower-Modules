<?php

namespace ModulesGarden\WordpressManager\App\UI\LoggerManager\Forms;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\WordpressManager\App\UI\LoggerManager\Providers\LoggerDataProvider;

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
        $this->setProvider(new LoggerDataProvider());

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
