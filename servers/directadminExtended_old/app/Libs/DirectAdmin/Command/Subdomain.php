<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Subdomain extends AbstractCommand
{
    const CMD_SUBDOMAIN = 'CMD_API_SUBDOMAINS';
    const CMD_SUBDOMAIN_V2 = 'CMD_SUBDOMAIN';
    const CMD_SHOW_LOG = 'CMD_SHOW_LOG';


    /**
     * get subdomains list
     *
     * @param Models\Command\Subdomain $subdomain
     * @return mixed
     */
    public function lists(Models\Command\Subdomain $subdomain)
    {
        return $this->curl->request(self::CMD_SUBDOMAIN_V2,[] , [
            'domain' => $subdomain->getDomain(),
            'json' => 'yes'
        ]);
    }

    /**
     * create subdomain
     *
     * @param Models\Command\Subdomain $subdomain
     * @return mixed
     */
    public function create(Models\Command\Subdomain $subdomain)
    {
        return $this->curl->request(self::CMD_SUBDOMAIN, [
            'action'    => __FUNCTION__,
            'domain'    => $subdomain->getDomain(),
            'subdomain' => $subdomain->getName()
        ]);
    }

    /**
     * delete subdomain
     *
     * @param Models\Command\Subdomain $subdomain
     * @return mixed
     */
    public function delete(Models\Command\Subdomain $subdomain)
    {
        return $this->curl->request(self::CMD_SUBDOMAIN, [
            'action'    => __FUNCTION__,
            'domain'    => $subdomain->getDomain(),
            'select0'   => $subdomain->getName(),
            'contents'  => $subdomain->getContents()
        ]);
    }

    public function getLogs(Models\Command\Subdomain $subdomain)
    {
        $data = [
            'domain' => $subdomain->getDomain(),
            'type' => $subdomain->getType(),
            'subdomain' => $subdomain->getSubdomain()
        ];

        $response = $this->curl->customRequest(self::CMD_SHOW_LOG, [], $data);
        return $this->loadResponse(new Models\Command\Logs(), $response, __FUNCTION__);
    }
}