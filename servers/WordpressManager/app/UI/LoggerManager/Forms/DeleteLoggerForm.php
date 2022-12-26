<?php

namespace ModulesGarden\WordpressManager\App\UI\LoggerManager\Forms;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\FormConstants;
use \ModulesGarden\WordpressManager\App\UI\LoggerManager\Providers\LoggerDataProvider;

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
        $this->setProvider( new LoggerDataProvider());

        $this->setConfirmMessage('confirmLabelRemoval');

        $field = new Hidden();
        $field->setId('id');
        $field->setName('id');
        $this->addField($field);

        $this->loadDataToForm();
    }
}
