<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Decorators\Account;

use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\OvhVpsAndDedicated\Core\ServiceLocator;

/**
 * Class Suspend
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Suspend
{
    public static function decorateSuccess($row)
    {
        $client = new Client($row['request']);
        $lang = ServiceLocator::call('lang');

        $lang->addReplacementConstant('machineName', $client->getServiceName());
        $lang->addReplacementConstant('service', $client->getServiceWithAnchor());
        $lang->addReplacementConstant('client', $client->getFullUserNameWithAnchor());
        $message = $lang->translate('logs', 'account' ,'suspend', 'success');


        return html_entity_decode($message);
    }

    public static function decorateError($row)
    {

        $client = new Client($row['request']);
        $lang = ServiceLocator::call('lang');

        $lang->addReplacementConstant('machineName', $client->getServiceName());
        $lang->addReplacementConstant('service', $client->getServiceWithAnchor());
        $lang->addReplacementConstant('client', $client->getFullUserNameWithAnchor());
        $lang->addReplacementConstant('messageName', $row['response']['errorMessage']);

        $message = $lang->translate('logs', 'account' ,'suspend', 'error');

        return html_entity_decode($message);
    }

}