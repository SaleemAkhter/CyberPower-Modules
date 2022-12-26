<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Ovh\ServerSettings;

/**
 * Class Server
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ServerSettingsManage
{

    protected function saveSettings($serverId = null, $setting = null, $value = null)
    {
        $serverSetting = new ServerSettings();
        $serverSetting->server_id = $serverId;
        $serverSetting->setting = $setting;
        $serverSetting->value = $value;
        $serverSetting->save();
    }

    public function saveServerSettings($settings)
    {
        $serverId = $settings[Constants::SERVER_ID];
        unset($settings[Constants::SERVER_ID]);

        foreach ($settings as $setting => $value)
        {
            $this->saveOrUpdateSetting($serverId,$setting, $value);
        }
    }

    public function saveOrUpdateSetting($serverId, $setting, $value)
    {
        $record = ServerSettings::where(Constants::SETTING, '=', $setting)->where(Constants::SERVER_ID, '=', $serverId);
        $result = $record->first();

        if(empty($result))
        {
            $this->saveSettings($serverId, $setting, $value);
            return;
        }
        $record->update([Constants::VALUE => $value]);
    }

    public static function getValueIfSetting($serverId, $setting)
    {
        if(is_null($serverId))
        {
            return false;
        }
        return ServerSettings::where(Constants::SETTING, '=', $setting)->where(Constants::SERVER_ID, '=', $serverId)->value(Constants::VALUE);
    }

}