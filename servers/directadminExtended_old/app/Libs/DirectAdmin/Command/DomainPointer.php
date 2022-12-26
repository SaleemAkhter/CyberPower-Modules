<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class DomainPointer extends AbstractCommand
{
    const CMD_DOMAIN_POINTER = 'CMD_API_DOMAIN_POINTER';
    const CMD_DOMAIN_POINTER_V2 = 'CMD_DOMAIN_POINTER';

    /**
     * get domain pointers list
     *
     * @param Models\Command\DomainPointer $domainPointer
     * @return mixed
     */
    public function lists(Models\Command\DomainPointer $domainPointer)
    {
        $response = $this->curl->request(self::CMD_DOMAIN_POINTER, [
            'domain' => $domainPointer->getDomain()
        ]);

        return $this->loadResponse(new Models\Command\DomainPointer(), $response);
    }

    /**
     * add domain pointer
     *
     * @param Models\Command\DomainPointer $domainPointer
     * @return mixed
     */
    public function add(Models\Command\DomainPointer $domainPointer)
    {
        return $this->curl->request(self::CMD_DOMAIN_POINTER, [
            'action'    => __FUNCTION__,
            'domain'    => $domainPointer->getDomain(),
            'from'      => $domainPointer->getFrom(),
            'alias'     => $domainPointer->getAlias()
        ]);
    }

    /**
     * delete domain pointer
     *
     * @param Models\Command\DomainPointer $domainPointer
     * @return mixed
     */
    public function delete(Models\Command\DomainPointer $domainPointer)
    {
        return $this->curl->request(self::CMD_DOMAIN_POINTER, [
            'action'    => __FUNCTION__,
            'domain'    => $domainPointer->getDomain(),
            'select0'   => $domainPointer->getFrom()
        ]);
    }

    public function deleteMany($deleteData)
    {
        $data = [
            'json'    => 'yes',
            'action' => 'delete'
        ];

        foreach($deleteData as $key => $value) {
            if($data['domain'] !== $key || empty($data['domain']))
            {
                $data['domain'] = $key;

                foreach($value as $elem => $each)
                {
                    $data['select'.$elem] = $each;
                }
                $this->curl->request(self::CMD_DOMAIN_POINTER_V2, $data);
            }
        }
    }
}