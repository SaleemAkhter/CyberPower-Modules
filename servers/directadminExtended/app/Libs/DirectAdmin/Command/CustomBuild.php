<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class CustomBuild extends AbstractCommand
{
    const CMD_PLUGINS_ADMIN  = 'CMD_PLUGINS_ADMIN';
    const CMD_SERVICE        = 'CMD_SERVICE';

    public function getUpdates($type='apps')
    {

        $response = $this->curl->request(self::CMD_PLUGINS_ADMIN.'/custombuild/vue_api.raw', [], [
            'get' => $type,
            'json'=>'yes'
        ]);
        return $response;
    }

    public function updateapp($app)
    {
        $response = $this->curl->streamrequest(self::CMD_PLUGINS_ADMIN.'/custombuild/build_software_command_sse.raw', [], [
            'command'=>$app,
        ]);
        return $response;
    }
    public function killprocess($app)
    {
        $response = $this->curl->request(self::CMD_PLUGINS_ADMIN.'/custombuild/kill_command.raw', [], [
            'json'=>'yes'
        ]);
        return $response;
    }
    public function getApiUrl($app)
    {

        $response = $this->curl->getApiUrl(self::CMD_PLUGINS_ADMIN.'/custombuild/build_software_command_sse.raw', [], [
            'command'=>$app,
        ]);



        return $response;
    }
    public function updateOptions($data)
    {
        $response = $this->curl->login()->custombuildrequest(self::CMD_PLUGINS_ADMIN."/custombuild/update_command.raw", $data, [
            'json'=>'yes'
                    ],true,true);
        return $response;
    }



}
