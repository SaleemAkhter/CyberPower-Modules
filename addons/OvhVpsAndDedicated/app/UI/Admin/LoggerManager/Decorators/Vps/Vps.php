<?php


namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Decorators\Vps;


use ModulesGarden\OvhVpsAndDedicated\Core\ServiceLocator;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;

class Vps
{

    public static function reassinged($row){
        $client = new Client($row['request']['params']);
        $lang = ServiceLocator::call('lang');

        $lang->addReplacementConstant('machineName', $client->getServiceName());
        $lang->addReplacementConstant('service', $client->getServiceWithAnchor());
        $lang->addReplacementConstant('client', $client->getFullUserNameWithAnchor());
        $message = $lang->translate('logs', 'vps', 'reassinged');


        return html_entity_decode($message);
    }
}