<?php


namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api;


use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\Constants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\ServerSettingsManage;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\WhmcsParams;
use Ovh\Api;

class OvhApiFactory
{

    use WhmcsParams;

    /**
     * @return Api
     * @throws \Ovh\Exceptions\InvalidParameterException
     */
    public function formParams(){
        $serverId = $this->getWhmcsParamByKey('serverid');
        $applicationKey = $this->getWhmcsParamByKey('serverusername');
        $applicationSecret = $this->getWhmcsParamByKey('serverpassword');
        $consumerKey =  $this->getWhmcsParamByKey('serveraccesshash');
        $apiEndpoint = ServerSettingsManage::getValueIfSetting($serverId, Constants::ENDPOINT);
        return new Api(
            $applicationKey,
            $applicationSecret,
            $apiEndpoint,
            $consumerKey
        );
    }
}