<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class PluginManager extends AbstractCommand
{
    const CMD_PLUGIN_MANAGER  = 'CMD_PLUGIN_MANAGER';
    const CMD_SERVICE        = 'CMD_SERVICE';

    public function list()
    {

        $response = $this->curl->request(self::CMD_PLUGIN_MANAGER, [], [
            'ipp' => 1000,
            'json'=>'yes',
        ]);
        return $response;
    }

    public function doCommand($data)
    {

        $response = $this->curl->request(self::CMD_SERVICE, $data, [
            'json'=>'yes',
        ]);
        return $response;
    }
    public function action($data)
    {

        $response = $this->curl->request(self::CMD_PLUGIN_MANAGER, $data, [
            'json'=>'yes',
        ]);
        return $response;
    }

    public function upload(Models\Command\FileManager $fileManager ,$additionaldata)
    {
        return $this->curl->request(self::CMD_PLUGIN_MANAGER, [
            'enctype'   => "multipart/form-data",
            'name'      => $fileManager->getName(),
            'passwd'       => $additionaldata['password'],
            'json'         => $additionaldata[''],
            'action'=>$additionaldata['action'],
            'install'=>$additionaldata['install'],
            'type'=>$additionaldata['type'],
            'file1'=>$fileManager->getFile(),
        ]);

    }



}
