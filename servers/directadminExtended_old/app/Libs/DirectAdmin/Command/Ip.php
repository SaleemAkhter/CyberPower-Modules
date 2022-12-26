<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Ip extends AbstractCommand
{
    const CMD_RESELLER_IPS  = 'CMD_API_SHOW_RESELLER_IPS';
    const CMD_IP_MANAGER    = 'CMD_IP_MANAGER';

    /**
     * get ip list
     *
     * @return mixed
     */
    public function getIpList()
    {
        $response = $this->curl->request(self::CMD_RESELLER_IPS, [], [
            'ipp' => '100000'
        ]);

        return $this->loadResponse(new Models\Command\Ip(), $response);
    }

    public function getFreeIp()
    {
        $response = $this->curl->request(self::CMD_IP_MANAGER, [] ,[
          'json' => 'yes',
          'ipp' => '100000'
        ]);

        return $this->loadResponse(new Models\Command\Ip(), $response, 'freeIp');
    }

    /**
     * get information about specific ip
     *
     * @param Models\Command\Ip $ip
     * @return mixed
     */
    public function getIpInformation(Models\Command\Ip $ip)
    {
        $response =  $this->curl->request(self::CMD_RESELLER_IPS, [
            'ip'    => $ip->getAddress()
        ]);

        return $this->loadResponse(new Models\Command\Ip(), $response);
    }
}