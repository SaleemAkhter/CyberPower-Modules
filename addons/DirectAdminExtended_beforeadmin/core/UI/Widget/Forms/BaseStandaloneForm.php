<?php

namespace ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms;

use \ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonSubmitForm;

/**
 * BaseForm controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseStandaloneForm extends BaseForm implements \ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface, \ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\FormInterface
{
    protected $id   = 'baseStandaloneForm';
    protected $name = 'baseStandaloneForm';
    protected $containerColClass='lu-col-md-12';

    public function __construct($baseId = null)
    {
        parent::__construct($baseId);

        $this->getAllowedActions();

        $submitButton = new ButtonSubmitForm();
        $submitButton->setFormId($this->id);
        $submitButton->runInitContentProcess();
        $this->setSubmit($submitButton);
    }

    protected function loadDataToForm()
    {
        $this->loadProvider();
        $this->dataProvider->initData();
        foreach ($this->fields as &$field)
        {
            $field->setValue($this->dataProvider->getValueById($field->getId()));
            $avValues = $this->dataProvider->getAvailableValuesById($field->getId());
            if ($avValues && method_exists($field, 'setAvailableValues'))
            {
                $field->setAvailableValues($avValues);
            }

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
    public function setContainerColClasss($classes)
    {
        if(is_array($classes)){
            $classes=implode(" ",$classes);
        }
        $this->containerColClasss=$classes;
        return $this;
    }
    public function getContainerColClasss($classes)
    {
        return $this->containerColClasss;
    }
}
