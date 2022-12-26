<?php


namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Vps;


use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\Constants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\ServerSettingsManage;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\OvhApiFactory;
use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\sl;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\AjaxFields\Select;
use Ovh\Api;

class LicenseSelect extends Select implements AdminArea
{

    /**
     * @var Api
     */
    private $api;
    /**
     * @var FieldsProvider
     */
    private $fieldsProvider;
    public function prepareAjaxData()
    {
        $this->setAvailableValues([
            ['key' => '', 'value' => sl("lang")->abtr("None")]
        ]);
        $this->api = (new OvhApiFactory)->formParams();
        $this->fieldsProvider = new FieldsProvider($this->getRequestValue('id'));
        $ovhSubsidiary = ServerSettingsManage::getValueIfSetting($this->getWhmcsParamByKey('serverid'), Constants::OVH_SUBSIDIARY);
        $planCode  = $this->getRequestValue('packageconfigoption_vpsProduct') ?  $this->getRequestValue('packageconfigoption_vpsProduct') : $this->fieldsProvider->getField('vpsProduct');
        if(!$planCode ){
            return;
        }
        $response = $this->api->get("/order/catalog/formatted/vps",["ovhSubsidiary" =>   $ovhSubsidiary]);
        foreach ($response['plans'] as $plan) {
            if($plan['planCode'] != $planCode)
            {
                continue;
            }
            foreach ($plan['addonsFamily'] as $addonsFamily)
            {
                if(!in_array($addonsFamily['family'], ['cpanel', 'plesk','windows'])){
                    continue;
                }
                $family = $addonsFamily['family'];
                foreach ($addonsFamily['addons'] as $addon)
                {
                    $this->availableValues[] =[
                        'key' =>  $addon['plan']['planCode'],
                        'value' => $addon['invoiceName']
                    ];
                }
            }
        }
        $this->setSelectedValue($this->fieldsProvider->getField('license'));
    }
}