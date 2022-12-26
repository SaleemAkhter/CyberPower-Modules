<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\ErrorHandler\Exceptions\ApiException;

class FileEditor extends AbstractCommand
{
    const CMD_ADMIN_FILE_EDITOR  = 'CMD_ADMIN_FILE_EDITOR';

    public function listAll($tab='basic')
    {
        $response = $this->curl->request(self::CMD_ADMIN_FILE_EDITOR, [], [
            'ipp' => 100000000,
            'json'=>'yes'
        ]);
        return $response;
    }
    public function save($data)
    {

        $response = $this->curl->request(self::CMD_ADMIN_FILE_EDITOR, $data, [
            'json'=>'yes'
        ]);
        return $response;
    }

    public function get($name)
    {
        try {
            $response = $this->curl->request(self::CMD_ADMIN_FILE_EDITOR, [], [
            'json'=>'yes',
            'file'=>$name
        ]);
        } catch (ApiException $e) {
            return $e->getMessage();
        }

        return $response;
    }

}
