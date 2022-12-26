<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Package extends AbstractCommand
{
    const CMD_USER     = 'CMD_API_PACKAGES_USER';
    const CMD_RESELLER = 'CMD_API_PACKAGES_RESELLER';

    /**
     * get user packages list
     *
     * @return mixed
     */
    public function getUserPackages()
    {
        $response = $this->curl->request(self::CMD_USER);

        return $this->loadResponse(new Models\Command\Package(), $response);
    }

    public function getResellersPackages()
    {
        $response = $this->curl->request(self::CMD_RESELLER);
        return $this->loadResponse(new Models\Command\Package(), $response);
    }
}
