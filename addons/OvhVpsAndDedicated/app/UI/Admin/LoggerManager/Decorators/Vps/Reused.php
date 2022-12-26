<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Decorators\Vps;

use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\Service;
use ModulesGarden\OvhVpsAndDedicated\Core\ServiceLocator;
use ModulesGarden\OvhVpsAndDedicated\Core\Traits\Lang;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;

/**
 * Class UpgradeDowngrade
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Reused
{

    public static function decorate($row)
    {
        $client = new Client($row['request']['params']);
        $lang = ServiceLocator::call('lang');

        $lang->addReplacementConstant('machineName', $client->getServiceName());
        $lang->addReplacementConstant('client', $client->getFullUserNameWithAnchor());
        $message = $lang->translate('logs', 'vps', 'reuse', 'success');


        return html_entity_decode($message);
    }



}