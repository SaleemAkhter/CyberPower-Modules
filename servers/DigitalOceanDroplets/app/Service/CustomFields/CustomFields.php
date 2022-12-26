<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Service\CustomFields;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\CustomField;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\CustomFieldValue;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Hosting;

/**
 * Description of CustomFields
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class CustomFields
{
    /*
     * Set Custom Field
     * 
     * $params string $fieldName, $value
     */

    private static function getService($serviceID)
    {
        return Hosting::where('id', $serviceID)->first();
    }

    public static function set($serviceID, $fieldName, $value = 0)
    {
        $field = self::getFieldValue($serviceID, $fieldName);
        if (empty($field))
        {
            $field = self::generateField($serviceID, $fieldName);
        }
        $field->value = $value;
        $field->save();
    }

    /*
     * Get Custom Field
     * 
     * $params string $fieldName

     * @return object insteadof CustomFields
     */

    public static function get($serviceID, $fieldName)
    {
        return self::getFieldValue($serviceID, $fieldName)->value;
    }

    /*
     * Create new Custom Fields
     * 
     * $params string $fieldName  integer $productID
     */

    public static function create($productID, $fieldName, $fieldType = 'text')
    {
        $fieldName = explode('|', $fieldName);
        $field     = self::getField($fieldName[0], $productID);
        if (is_null($field))
        {
            self::createField('product', $productID, implode('|', $fieldName), $fieldType);
        }
    }

    private static function getFieldValue($serviceID, $fieldName)
    {
        $productID = self::getService($serviceID)->packageid;
        return CustomFieldValue::where('relid', $serviceID)->with('field')->whereHas('field', function($q) use($fieldName, $productID)
                {
                    $q->where('fieldname', 'LIKE', $fieldName . '%');
                    $q->where('relid', $productID);
                })->first();
    }

    private static function getField($fieldName, $productID)
    {
        return CustomField::where([
                    ['fieldname', 'LIKE', $fieldName . '%'],
                    ['relid', $productID],
                ])->first();
    }

    /*
     * Check and generate if not exists custom field
     * 
     * $params string $fieldName
     * @return object insteadof CustomFields
     */

    private static function generateField($serviceID, $fieldName)
    {
        $productID = self::getService($serviceID)->packageid;
        $field     = self::getField($fieldName, $productID);
        if (empty($field))
        {
            $field = self::createField('product', $productID, $fieldName);
        }
        return self::createFieldValue($field->id, $serviceID);
    }

    /*
     * Create custom field
     * 
     * $params integer $relID string $type, $fieldName, $fieldType, $adminOnly
     * @return object insteadof CustomFields
     */

    private static function createField($type, $relID, $fieldName, $fieldType = "text", $adminOnly = "on")
    {
        $customField            = new CustomField();
        $customField->type      = $type;
        $customField->relid     = $relID;
        $customField->fieldname = $fieldName;
        $customField->fieldtype = $fieldType;
        $customField->adminonly = $adminOnly;
        $customField->save();
        return $customField;
    }

    /*
     * Create custom field values
     * 
     * $params integer $fieldID, $relID
     * @return object insteadof CustomFieldsValues
     */

    private static function createFieldValue($fieldID, $relID)
    {
        $customFieldValue          = new CustomFieldValue();
        $customFieldValue->fieldid = $fieldID;
        $customFieldValue->relid   = $relID;
        $customFieldValue->save();
        return $customFieldValue;
    }

}
