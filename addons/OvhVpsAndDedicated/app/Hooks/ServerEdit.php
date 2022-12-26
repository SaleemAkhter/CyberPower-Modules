<?php

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\Constants;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\ServerSettingsManage;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\Details;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\AutoObtainConsumerKey;

/**
 * Description of AdminAreaFooterOutput
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
$hookManager->register(
    function ($args)
    {
        $settings                       = Details::getDataToSaveFromRequest();
        $settings[Constants::SERVER_ID] = $args['serverid'];
        $server                         = new ServerSettingsManage();
        $server->saveServerSettings($settings);


        $autoObtainConsumerKey = new AutoObtainConsumerKey($settings, $args['serverid']);
        $autoObtainConsumerKey->run();
    },
    100
);
