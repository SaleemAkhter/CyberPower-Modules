<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;
use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\sl;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Base\Dedicated\DedicatedConfigSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\AjaxFields\Select;

/**
 * Class DedicatedSystemTemplates
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 * @property  Api $api
 */
class DataCenterSelect extends DedicatedConfigSelect implements AdminArea
{


    public function initContent()
    {
        $this->initIds("packageconfigoption_dataCenter");
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
        $request = [
            'planCode' => $planCode
        ];
        $memory  = $this->getRequestValue('packageconfigoption_memory') ?  $this->getRequestValue('packageconfigoption_memory') : $this->fieldsProvider->getField('memory');
        if($memory){
            //$request['memory'] = $memory;
        }
        $storage  = $this->getRequestValue('packageconfigoption_storage') ?  $this->getRequestValue('packageconfigoption_storage') : $this->fieldsProvider->getField('storage');
        if($storage){
            //$request['storage'] = $storage;
        }
        //get data center
        $response  = $this->config
                          ->getApi()
                           ->dedicated
                           ->server()
                          ->get("datacenter/availabilities",$request);
        $dataCenter=[];
        foreach ($response as $entity){
            foreach ($entity['datacenters'] as $data) {
                $dataCenter[$data['datacenter']] =$data['datacenter'];
            }
        }
        foreach ( $dataCenter as $item) {
            $this->availableValues[] = [
                'key'   => $item,
                'value' =>  sl('lang')->absoluteT('mainContainer','configurableOptions', $item),
            ];
        }
        //set selected value
        $this->setValue( $this->fieldsProvider->getField('dataCenter'));
    }

}
