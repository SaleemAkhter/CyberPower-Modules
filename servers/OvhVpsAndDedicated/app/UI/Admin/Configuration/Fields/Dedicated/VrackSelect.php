<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Base\Dedicated\DedicatedConfigSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\AjaxFields\Select;

/**
 * Class DedicatedSystemTemplates
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 * @property  Api $api
 */
class VrackSelect extends DedicatedConfigSelect implements AdminArea
{


    public function initContent()
    {
        $this->initIds("packageconfigoption_vrack");
    }


    public function prepareAjaxData()
    {
        if($this->config == null)
        {
            return;
        }
        $planCode  = $this->getRequestValue('packageconfigoption_planCode') ?  $this->getRequestValue('packageconfigoption_planCode') : $this->fieldsProvider->getField('planCode');
        if(!$planCode ){
            return;
        }
        //create order cart
        $params = [ 'ovhSubsidiary' => $this->config->getApi()->getClient()->getCountry() ];
        $cartId = $this->config->getApi()->order->post('cart',$params)['cartId'];
        //get plan options
        $response  = $this->config->getApi()->order->get(sprintf("cart/%s/baremetalServers/options", $cartId),['planCode' => $planCode]);
        foreach ($response as $plan){
            if($plan['family']!="vrack"){
                continue;
            }
            $this->availableValues[] = [
                'key'   => $plan['planCode'],
                'value' =>  sprintf("%s", $plan['productName']),
            ];
        }
        //set selected value
        $this->setValue( $this->fieldsProvider->getField('vrack'));
    }

}
