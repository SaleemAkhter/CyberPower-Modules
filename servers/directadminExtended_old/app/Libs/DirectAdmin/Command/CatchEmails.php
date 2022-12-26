<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\CatchEmails as Model;


class CatchEmails extends AbstractCommand
{
    const CATCH_ALL_EMAIL = 'CMD_EMAIL_CATCH_ALL';

    public function edit(Model $data)
    {
        $requestData =
        [
            'json' => 'yes',
            'domain' => $data->getDomain(),
            'update' => 'yes',
            'catch' => $data->getOption(),
            'value' => $data->getEmail()
        ];
        $this->curl->request(self::CATCH_ALL_EMAIL, $requestData);
    }

    public function getCatchEmail(Model $data)
    {
        $requestData =
        [
            'json' => 'yes',
            'domain' => $data->getDomain()
        ];
        return  $this->curl->request(self::CATCH_ALL_EMAIL, [],$requestData);
    }
}