<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Ovh\ProductConfiguration;

/**
 * Description of FieldsProvider
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class FieldsProvider
{

    protected $productID;

    public function __construct($productID)
    {
        $this->productID = (int) $productID;
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

    public function getApplicationLicense(){
        return \json_decode($this->getField('applicationLicense'));
    }

    public function getDataCenter(){
        return $this->getField('dataCenter');
    }

    public function getDedicatedOs(){
        return $this->getField('dedicatedSystemTemplates');
    }

    public function getServiceInformation(){
        return \json_decode($this->getField('serviceInformation'), true);
    }
}
