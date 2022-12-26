<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\BaseSubmitButton;

/**
 * BaseForm controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseStandaloneForm extends BaseForm implements \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AjaxElementInterface, \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\FormInterface
{
    protected $id   = 'baseStandaloneForm';
    protected $name = 'baseStandaloneForm';

    public function __construct()
    {
        parent::__construct();

        $this->getAllowedActions();

        $submitButton = new BaseSubmitButton();
        $submitButton->setFormId($this->id);
        $submitButton->initContent();
        $this->setSubmit($submitButton);
    }

    protected function loadDataToForm()
    {
        foreach ($this->fields as &$field)
        {
            $this->loadProvider();
            $field->setValue($this->dataProvider->getValueById($field->getId()));
            if ($this->dataProvider->isDisabledById($field->getId()))
            {
                $field->disableField();
            }
        }

        foreach ($this->sections as &$section)
        {
            $section->loadDataToForm($this->dataProvider);
        }

        $this->addLangReplacements();
    }
}
