<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Decorators\Vps;

use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\Service;
use ModulesGarden\OvhVpsAndDedicated\Core\ServiceLocator;
use ModulesGarden\OvhVpsAndDedicated\Core\Traits\Lang;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Whmcs\Hosting;

/**
 * Class UpgradeDowngrade
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class EmailActionTemplate
{


    public static function decorateActionNotFound($row)
    {
        $hosting = Hosting::where('id', $row['request']['id'])->first();

        $serviceLink = sprintf('<a href="clientsservices.php?userid=%s&productselect=%s">#%s %s</a>',
            $hosting->userid,
            $hosting->id,
            $hosting->id,
            $hosting->domain);;

        $lang = ServiceLocator::call('lang');
        $lang->addReplacementConstant('service', $serviceLink);
        $message = $lang->translate('logs', 'vps', 'email', 'error','actionNotFound');

        $message = html_entity_decode($message);


        return $message;
    }
}