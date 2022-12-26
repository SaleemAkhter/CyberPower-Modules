<?php

namespace ModulesGarden\Servers\HetznerVps\App\Helpers;

use ModulesGarden\Servers\HetznerVps\App\Models\ProductConfiguration;

/**
 * Description of FieldsProvider
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class FieldsProvider
{

    protected $productID;

    public function __construct($productID)
    {
        $this->productID = $productID;
    }

    public function getFields()
    {
        return ProductConfiguration::whereHostingID($this->productID)->get();
    }

    public function getField($fieldName, $default = "")
    {
        $field = ProductConfiguration::where([
                    ['product_id', $this->productID],
                    ['setting', $fieldName]
                ])->first();
        if (is_null($field))
        {
            return $default;
        }

        return $field->value;
    }

    public function saveAll($fields)
    {

        if(is_null($fields)){
            return;
        }
        $this->removeAll();
        foreach ($fields as $key => $value)
        {

            $this->save($key, (is_array($value) ? json_encode($value): $value));
        }
    }
    public function removeAll(){
        ProductConfiguration::where('product_id', $this->productID)->delete();
    }

    public function save($fieldName, $fieldValue)
    {
        $field = ProductConfiguration::updateOrCreate(
                        ['product_id' => $this->productID, 'setting' => $fieldName], ['value' => $fieldValue]
        );
    }

}
