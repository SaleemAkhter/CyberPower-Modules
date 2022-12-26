<?php

namespace ModulesGarden\Servers\HetznerVps\App\Helpers;

use ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\CustomField;
use ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\CustomFieldValue;
use ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\Hosting;

/**
 * Description of CustomFields
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class CustomFields
{

    private static function getProductID($serviceID)
    {
        return Hosting::where('id', $serviceID)->select('packageid')->first()->packageid;
    }

    /*
     * Set Custom Field
     * 
     * $params string $fieldName, $value
     */

    public static function set($serviceID, $fieldName, $value = 0)
    {
        $productID = self::getProductID($serviceID);

        $field = self::getFieldValue($productID, $serviceID, $fieldName);
        if (empty($field))
        {
            $field = self::generateField($productID, $serviceID, $fieldName);
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
        $productID = self::getProductID($serviceID);
        return self::getFieldValue($productID, $serviceID, $fieldName)->value;
    }

    /*
     * Create new Custom Fields
     * 
     * $params string $fieldName  integer $productID
     */

    public static function create($productID, $fieldName, $fieldType = "text", $adminOnly = "on", $showorder = "", $description = "", $regexpr = "")
    {

        $fieldName = explode('|', $fieldName);
        $field     = self::getField($productID, $fieldName[0]);
        if (is_null($field))
        {
            self::createField('product', $productID, implode('|', $fieldName), $fieldType, $adminOnly, $showorder, $description, $regexpr);
        }
    }

    private static function getFieldValue($productID, $serviceID, $fieldName)
    {

        return CustomFieldValue::where('relid', $serviceID)->with('field')->whereHas('field', function($q) use($fieldName, $productID)
                {
                    $q->where('fieldname', 'LIKE', $fieldName . '%');
                    $q->where('relid', $productID);
                })->first();
    }

    private static function getField($productID, $fieldName)
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

    private static function generateField($productID, $serviceID, $fieldName)
    {
        $field = self::getField($productID, $fieldName);
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
     * @return object insteadof CustomField
     */

    private static function createField($type, $relID, $fieldName, $fieldType = "text", $adminOnly = "on", $showorder = "", $description = "", $regexpr = "")
    {
        $customField              = new CustomField();
        $customField->type        = $type;
        $customField->relid       = $relID;
        $customField->fieldname   = $fieldName;
        $customField->fieldtype   = $fieldType;
        $customField->adminonly   = $adminOnly;
        $customField->showorder   = $showorder;
        $customField->description = $description;
        $customField->regexpr        = $regexpr;
        $customField->save();
        return $customField;
    }

    /*
     * Create custom field values
     * 
     * $params integer $fieldID, $relID
     * @return object insteadof CustomFieldValue
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
