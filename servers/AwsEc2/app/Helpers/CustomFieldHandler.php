<?php


namespace ModulesGarden\Servers\AwsEc2\App\Helpers;


use ModulesGarden\Servers\AwsEc2\Core\HandlerError\Exceptions\Exception;

class CustomFieldHandler
{
    protected $fieldRelId = null;
    protected $fieldType = null;
    protected $fieldName = null;

    public function __construct($fieldRelId = null, $fieldType = null, $name = null)
    {
        $this->setFieldRelId($fieldRelId);
        $this->setFieldType($fieldType);
        $this->setFieldName($name);

        $this->initField();
    }

    public function updateCustomField($fieldName, $fieldValue = '')
    {
        if (!in_array($fieldName, ProvisioningConstants::getCustomFieldsList()))
        {
            return false;
        }

        $fieldModel = new CustomField();
        //$fieldModel->where()

        die(var_dump($fieldModel));
    }

    public function setFieldRelId($fieldRelId = null)
    {
        $relationId = (int)$fieldRelId;
        if ($relationId <= 0)
        {
            throw new Exception(ErrorCodesLib::AWS_EC2_000001);
        }

        $this->fieldRelId = $relationId;
    }

    public function setFieldType($fieldType = null)
    {
        if (!in_array($fieldType, ProvisioningConstants::getCustomFieldsTypesList()))
        {
            throw new Exception(ErrorCodesLib::AWS_EC2_000002);
        }

        $this->fieldType = $fieldType;
    }

    public function setFieldName($fieldName = null)
    {
        if (!in_array($fieldName, ProvisioningConstants::getCustomFieldsList()))
        {
            throw new Exception(ErrorCodesLib::AWS_EC2_000003);
        }

        $this->fieldName = $fieldName;
    }

    public function initField()
    {
        $fieldModel = new CustomField();
        $field =

        die(var_dump($fieldModel));
    }
}
