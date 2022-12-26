<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class PerlModules extends AbstractCommand
{
    const CMD_PERL_MODULES  = 'CMD_API_PERL_MODULES';

    /**
     * get perl modules list
     *
     * @return mixed
     */
    public function lists()
    {
        $response = $this->curl->request(self::CMD_PERL_MODULES);

        return $this->loadResponse(new Models\Command\PerlModules(), $response);
    }
}