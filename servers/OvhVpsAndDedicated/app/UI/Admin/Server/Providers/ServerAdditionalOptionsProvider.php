<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Server\Providers;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\Constants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\ServerSettingsManage;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\DataProvider;

/**
 * Class ServerAdditionalOptionsProvider
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ServerAdditionalOptionsProvider extends DataProvider
{
    public function getAdditionalServerData()
    {
        $id = $this->request->get('id');

        $vars = [
            Constants::ENDPOINTS        => self::getEndPoints(),
            Constants::OVH_SUBSIDIARIES => self::getOvhSubsidiary(),
            Constants::OVH_SERVER_TYPE  => self::getOvhServerTypes(),
            'selectedEndPoint'          => ServerSettingsManage::getValueIfSetting($id, Constants::ENDPOINT),
            'selectedCountry'           => ServerSettingsManage::getValueIfSetting($id, Constants::OVH_SUBSIDIARY),
            'selectedOvhServerType'     => ServerSettingsManage::getValueIfSetting($id, Constants::OVH_SERVER_TYPE),
        ];

        return $vars;
    }


    public static function getEndPoints()
    {
        return [
            'ovh-eu',
            'ovh-ca',
            'ovh-us',
            'kimsufi-eu',
            'kimsufi-ca',
            'soyoustart-eu',
            'soyoustart-ca',
            'runabove-ca',
        ];
    }

    public static function getOvhSubsidiary()
    {
        return [
            'ASIA',
            'AU',
            'CA',
            'CZ',
            'DE',
            'ES',
            'EU',
            'FI',
            'FR',
            'GB',
            'IE',
            'IT',
            'LT',
            'MA',
            'NL',
            'PL',
            'PT',
            'QC',
            'SG',
            'SN',
            'TN',
            'US',
            'WE',
            'WS'
        ];
    }

    public static function getOvhServerTypes()
    {
        return [
            Constants::VPS,
            Constants::DEDICATED,
        ];
    }
}