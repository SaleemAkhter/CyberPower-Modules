<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class DomainForwarder extends AbstractCommand
{
    const CMD_REDIRECT  = 'CMD_API_REDIRECT';
    const CMD_REDIRECT_V2  = 'CMD_REDIRECT';

    /**
     * get domain forwarders list
     *
     * @param Models\Command\DomainForwarder $domainForwarder
     * @return mixed
     */
    public function lists(Models\Command\DomainForwarder $domainForwarder)
    {
        return $this->curl->request(self::CMD_REDIRECT_V2, [
            'domain' => $domainForwarder->getDomain(),
            'json' => 'yes'
        ]);

        return $this->loadResponse(new Models\Command\DomainForwarder(), $response);
    }

    /**
     * add domain forwarder
     *
     * @param Models\Command\DomainForwarder $domainForwarder
     * @return mixed
     */
    public function add(Models\Command\DomainForwarder $domainForwarder)
    {
        return $this->curl->request(self::CMD_REDIRECT, [
            'action'    => __FUNCTION__,
            'domain'    => $domainForwarder->getDomain(),
            'from'      => $domainForwarder->getFrom(),
            'to'        => $domainForwarder->getTo(),
            'type'      => $domainForwarder->getType()
        ]);
    }

    /**
     * delete domain forwarder
     *
     * @param Models\Command\DomainForwarder $domainForwarder
     * @return mixed
     */
    public function delete(Models\Command\DomainForwarder $domainForwarder)
    {
        return $this->curl->request(self::CMD_REDIRECT, [
            'action'    => __FUNCTION__,
            'domain'    => $domainForwarder->getDomain(),
            'select0'   => $domainForwarder->getFrom()
        ]);
    }

    public function deleteMany($domainForwarder)
    {
        foreach($domainForwarder as $domain => $targetList)
        {
            $data = [];
            $data['domain'] = $domain;
            foreach ($targetList as $key => $user)
            {
                $data['select' . $key] = $user;
            }

            $this->curl->request(self::CMD_REDIRECT, array_merge([
                'action'    => 'delete'
            ], $data));
        }
    }
}