<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Forms;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonSubmitForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
/**
 * BaseForm controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BoxedForm extends BaseForm implements \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface, \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\FormInterface
{
    protected $id   = 'boxedForm';
    protected $name = 'boxedForm';
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
    public function setContainerColClass($value='')
    {
        return $this->containerColClass;
    }
    public function getContainerColClass($value='')
    {
        return $this->containerColClass;
    }
}
