<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\BaseField;

/**
 * Fields Elements related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait Fields
{
    /**
     * Fields List
     * @var Array
     */
    protected $fields = [];
    
    /**
     * List of validation errors
     * @var Array
     */    
    protected $validationErrors = [];

    /**
     * Adds field object to field list
     * @return $this
     */
    public function addField(BaseField $field)
    {
        $this->fields[$field->getId()] = $field;

        return $this;
    }

    /**
     * Returns Field object by field id
     * @return Field object
     */
    public function getField($fieldId)
    {
        return $this->fields[$fieldId];
    }

    /**
     * Returns Field objects array
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }
    
    /**
     * Validates data provided to form by valiators in each field
     * @return array
     */    
    public function validateFields($request)
    {
        foreach ($this->fields as $field)
        {
            $formData = $request->get('formData', []);
            $value = $this->convertStringToValue($field->getName(), $formData);
            if ($field->isValueValid($value, $request))
            {
                continue;
            }

            $this->validationErrors[$field->getName()] = $field->getValidationErrors();
        }
        
        $this->validateSections($request);
        
        return $this;
    }
    
    protected function convertStringToValue($name, $formData)
    {
        $nameArray = explode('[', str_replace(']', '', $name));
        return dot($formData)->get(implode('.', $nameArray), null);
    }
    
    public function validateSections($request)
    {
        foreach ($this->sections as $section)
        {
            $section->validateFields($request);
            if ($section->getValidationErrors())
            {
                $this->validationErrors = array_merge($this->validationErrors, $section->getValidationErrors());
            }
        }     
        
        return $this;
    }
    
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}
