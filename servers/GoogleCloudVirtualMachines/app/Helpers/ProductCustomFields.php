<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers\ErrorCodesLib;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\Exception;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Models\Whmcs\CustomField;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Models\Whmcs\CustomFieldValue;

class ProductCustomFields
{
    const FIELD_TYPE = 'product';

    protected $productId = null;

    protected $serviceId = null;

    protected $fieldsList = [];

    public function __construct($productId = null, $serviceId = null)
    {
        $this->setProductId($productId);
        $this->setServiceId($serviceId);

        $this->loadFieldsList();
    }

    public function getFieldsList()
    {
        return $this->fieldsList;
    }

    protected function setProductId($productId = null)
    {
        $relationId = (int)$productId;
        if ($relationId <= 0)
        {
            throw new Exception(ErrorCodesLib::IBM_CLOUD_VPS_000004);
        }

        $this->productId = $relationId;
    }

    protected function setServiceId($serviceId = null)
    {
        $relationId = (int)$serviceId;
        if ($relationId <= 0)
        {
            throw new Exception(ErrorCodesLib::IBM_CLOUD_VPS_000006);
        }

        $this->serviceId = $relationId;
    }

    protected function loadFieldsList()
    {
        $fieldModel = new CustomField();

        $fieldsResult = $fieldModel->where('type', self::FIELD_TYPE)
            ->where('relid', $this->productId)->get();

        if ($fieldsResult)
        {
            $list = $fieldsResult->toArray();

            $parsedList = [];
            foreach ($list as $field)
            {
                $parts = explode('|', $field['fieldname']);
                $parsedList[$parts[0]] = $field;
            }

            $this->fieldsList = $parsedList;
        }
    }

    public function updateFieldValue($fieldName, $newValue = '')
    {
        $nameParts = explode('|', $fieldName);

        $fieldName = $nameParts[0];
        if (!isset($this->fieldsList[$fieldName]))
        {
            throw new Exception(ErrorCodesLib::IBM_CLOUD_VPS_000005);
        }

        $field = $this->fieldsList[$fieldName];

        $customFieldValueModel = new CustomFieldValue();
        $count = $customFieldValueModel->where('fieldid', $field['id'])->where('relid', $this->serviceId)->count();

        if ($count > 0)
        {
            return $customFieldValueModel->where('fieldid', $field['id'])->where('relid', $this->serviceId)->update(['value' => $newValue]);
        }

        $customFieldValueModel = new CustomFieldValue();

        return $customFieldValueModel->fill([
            'fieldid' => $field['id'],
            'relid'=> $this->serviceId,
            'value' => $newValue
        ])->save();
    }
}
