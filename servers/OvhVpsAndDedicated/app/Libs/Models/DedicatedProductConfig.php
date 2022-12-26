<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Models\Product;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Order\Basics;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Serializer;

/**
 * Class ProductConfig
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class DedicatedProductConfig extends Serializer
{

    private $whmcsProductId;
    private $systemTemplate;
    private $language;


    public function __construct($params)
    {
        $this->whmcsProductId = $params['packageid'];

        $config  = $params['configoptions'];
        $product = new FieldsProvider($this->whmcsProductId);



        $this->systemTemplate = isset($config['dedicatedSystemTemplate']) ? $config['dedicatedSystemTemplate'] : $product->getField('dedicatedSystemTemplates');
        $this->language       = isset($config['dedicatedLanguage']) ? $config['dedicatedLanguage'] : $product->getField('dedicatedLanguages');

    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }



    /**
     * @return mixed
     */
    public function getWhmcsProductId()
    {
        return $this->whmcsProductId;
    }

    /**
     * @param mixed $whmcsProductId
     */
    public function setWhmcsProductId($whmcsProductId)
    {
        $this->whmcsProductId = $whmcsProductId;
    }

    /**
     * @return mixed
     */
    public function getSystemTemplate()
    {
        return $this->systemTemplate;
    }

    /**
     * @param mixed $systemTemplate
     */
    public function setSystemTemplate($systemTemplate)
    {
        $this->systemTemplate = $systemTemplate;
    }


}