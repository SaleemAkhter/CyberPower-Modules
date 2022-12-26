<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\Request;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;

/**
 * BaseForm controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseForm extends BaseContainer implements \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AjaxElementInterface, \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\FormInterface
{

    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Form;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Fields;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Sections;    
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\FormDataProvider;
    
    protected $id         = 'baseForm';
    protected $name       = 'baseForm';
    protected $formAction = null;
    protected $requestObj = null;

    public function returnAjaxData()
    {
        $this->loadProvider();
        $this->requestObj = Request::build();
        $this->formAction = $this->requestObj->get('mgFormAction', false);
        
        $resp = new ResponseTemplates\HtmlDataJsonResponse();

        $resp->setCallBackFunction($this->getCallBackFunction());
        
        if (!$this->isFormActionValid())
        {
            return $resp->setMessageAndTranslate('undefinedAction')->setStatusError();
        }

        if (!$this->validateForm())
        {
            $resp = new ResponseTemplates\RawDataJsonResponse();
            $resp->setCallBackFunction($this->getCallBackFunction());
            
            return $resp->setMessageAndTranslate('formValidationError')->setStatusError()->setData(['FormValidationErrors' => $this->validationErrors]);
        }

        try
        {
            $response = $this->dataProvider->{$this->formAction}();
            if ($response instanceof \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ResponseInterface)
            {
                return $response;
            }
        }
        catch (\Exception $exc)
        {
            return $resp->setMessage($exc->getMessage())->setStatusError();
        }

        return $resp->setMessageAndTranslate('changesHasBeenSaved');
    }

    protected function validateForm()
    {
        if ($this->formAction === FormConstants::READ || $this->formAction === FormConstants::REORDER)
        {
            return true;
        }

        $this->validateFields($this->requestObj);

        if (count($this->validationErrors) > 0)
        {
            return false;
        }

        return true;
    }

    protected function loadDataToForm()
    {
        if(!\ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\sl('request')->get('ajax'))
        {
            return; 
        }
        
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

    protected function isFormActionValid()
    {
        if ($this->formAction === false || !in_array($this->formAction, $this->getAllowedActions())
                || !method_exists($this->dataProvider, $this->formAction))
        {
            return false;
        }

        return true;
    }
    
    protected function loadDataToFormByName()
    {
        
        $this->loadProvider();
        foreach ($this->fields as &$field)
        {
            $field->setValue($this->dataProvider->getValueByName($field->getName()));
            if ($this->dataProvider->isDisabledById($field->getId()))
            {
                $field->disableField();
            }
        }

        foreach ($this->sections as &$section)
        {
            $section->loadDataToFormByName($this->dataProvider);
        }
        
        $this->addLangReplacements();
    }     
}
