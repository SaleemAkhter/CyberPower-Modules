<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Helper\Permissions\Permissions;

class FileManagerPermission extends FileManager
{

    public function read()
    {
        $data = json_decode(base64_decode($this->actionElementId));

        $explodeTruePath = explode('/', $data->truepath);
        $file            = end($explodeTruePath);
        $this->data['file']     = $file;
        unset($explodeTruePath[array_search($file, $explodeTruePath)]);
        $this->data['path'] = implode('/', $explodeTruePath);

        $permissionsOptions = Permissions::getDecodeInstance()->getOptions($data->permission);
        foreach ($permissionsOptions as $key => $val)
        {
            $this->data[$key] = $val;
        }
    }

}
