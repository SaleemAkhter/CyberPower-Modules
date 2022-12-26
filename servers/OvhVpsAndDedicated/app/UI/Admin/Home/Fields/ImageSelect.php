<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Fields;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Api\Blocker;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\OvhApiFactory;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\AjaxFields\Select;

class ImageSelect extends Select implements AdminArea, ClientArea
{

    public function prepareAjaxData()
    {
        $options = [];
        $serverName =  $this->getWhmcsParamByKey('customfields')['serverName'];
        $api = (new OvhApiFactory())->formParams();
        $bockedImages = (array) Blocker::getBlockedImages();
        foreach ($api ->get(sprintf('/vps/%s/images/available',$serverName)) as $imageId){
            $detail = $api->get(sprintf('/vps/%s/images/available/%s',$serverName, $imageId));
            if($detail['name'] && in_array($detail['name'], $bockedImages)){
                continue;
            }
            $options[]=[
                "key" => $imageId,
                "value" => $detail['name'],
            ];
        }
        $this->setAvailableValues($options);
        //current
        $current = $api ->get(sprintf('/vps/%s/images/current',$serverName));
        $this->setSelectedValue( $current['id']);
    }

}