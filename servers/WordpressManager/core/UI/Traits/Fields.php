<?php

namespace ModulesGarden\WordpressManager\Core\UI\Traits;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\BaseField;

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
    public function addField( $field)
    {
        $this->fields[$field->getId()] = $field;

        if ($field->isAjaxComponent())
        {
            $this->mainContainer->addAjaxElement($field);
        }

        if ($field->isVueComponent())
        {
            $this->mainContainer->addVueComponent($field);
        }
        
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

        return $this;
    }
    
    protected function convertStringToValue($name, $formData)
    {
        $nameArray = explode('[', str_replace(']', '', $name));
        return dot($formData)->get(implode('.', $nameArray), null);
    }
   
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
    
    /**
     * Removes field object from the fields list
     * @return $this
     */
    public function removeField($fieldId = null)
    {
        if ($fieldId && $this->fields[$fieldId])
        {
            unset($this->fields[$fieldId]);
        }

        return $this;
    }
}
