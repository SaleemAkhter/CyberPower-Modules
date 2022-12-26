<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Decorators\Vps;

use ModulesGarden\OvhVpsAndDedicated\Core\ServiceLocator;
use ModulesGarden\OvhVpsAndDedicated\Core\Traits\Lang;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;

/**
 * Class UpgradeDowngrade
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class UpgradeDowngrade
{
    use Lang;

    public static function decorate($row)
    {
        $client = new Client($row['request']['params']);
        $lang = ServiceLocator::call('lang');

        $lang->addReplacementConstant('machineName', $client->getServiceName());
        $lang->addReplacementConstant('client', $client->getFullUserNameWithAnchor());
        $message = $lang->translate('logs', 'vps', 'upgradeDowngrade', 'success');


        return html_entity_decode($message);
    }

    public static function decorateOptionAddingError($row)
    {
        $client = new Client($row['request']['params']);

        $option = $row['request']['option'];

        $lang = ServiceLocator::call('lang');
        $lang->addReplacementConstant('machineName', $client->getServiceName());
        $lang->addReplacementConstant('option', $option);
        $message = $lang->translate('logs', 'vps', 'upgradeDowngrade', 'optionAdding','error');

        $message = html_entity_decode($message);

        if($error = $row['response']['message'])
        {
            $message .= " Error: ". $error;
        }

        return $message;
    }

    public static function decorateSameOptionAsUsage($row)
    {
        $client = new Client($row['request']['params']);

        $option = $row['request']['option'];

        $lang = ServiceLocator::call('lang');
        $lang->addReplacementConstant('machineName', $client->getServiceName());
        $lang->addReplacementConstant('option', $option);
        $message = $lang->translate('logs', 'vps', 'upgradeDowngrade', 'optionAdding','sameAsUse');

        $message = html_entity_decode($message);

        if($error = $row['response']['message'])
        {
            $message .= " Error: ". $error;
        }

        return $message;
    }

}