<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class EmailForwarder extends AbstractCommand
{
    const CMD_EMAIL_FORWARDER  = 'CMD_API_EMAIL_FORWARDERS';
    const CMD_EMAIL_FORWARDER_V2  = 'CMD_EMAIL_FORWARDER';

    /**
     * get email forwarders list
     *
     * @param Models\Command\EmailForwarder $emailForwarder
     * @return mixed
     */
    public function lists(Models\Command\EmailForwarder $emailForwarder)
    {
        $response = $this->curl->request(self::CMD_EMAIL_FORWARDER, [
            'domain' => $emailForwarder->getDomain()
        ]);

        return $this->loadResponse(new Models\Command\EmailForwarder(), $response);
    }

    /**
     * create email forwarder
     *
     * @param Models\Command\EmailForwarder $emailForwarder
     * @return mixed
     */
    public function create(Models\Command\EmailForwarder $emailForwarder)
    {
        return $this->curl->request(self::CMD_EMAIL_FORWARDER, [
            'action'    => __FUNCTION__,
            'domain'    => $emailForwarder->getDomain(),
            'user'      => $emailForwarder->getUser(),
            'email'     => $emailForwarder->getEmail()
        ]);
    }

    /**
     * delete email forwarder
     *
     * @param Models\Command\EmailForwarder $emailForwarder
     * @return mixed
     */
    public function delete(Models\Command\EmailForwarder $emailForwarder)
    {
        return $this->curl->request(self::CMD_EMAIL_FORWARDER, [
            'action'    => __FUNCTION__,
            'domain'    => $emailForwarder->getDomain(),
            'select0'   => $emailForwarder->getUser()
        ]);
    }

    /**
     * modify email forwarder
     *
     * @param Models\Command\EmailForwarder $emailForwarder
     * @return mixed
     */
    public function modify(Models\Command\EmailForwarder $emailForwarder)
    {
        return $this->curl->request(self::CMD_EMAIL_FORWARDER, [
            'action'    => __FUNCTION__,
            'domain'    => $emailForwarder->getDomain(),
            'user'      => $emailForwarder->getUser(),
            'email'     => $emailForwarder->getEmail()
        ]);
    }

    public function deleteMany(array $deleteData)
    {
        $data = [
            'json'    => 'yes',
            'action' => 'delete',
            'delete' => 'yes'
        ];

        foreach($deleteData as $key => $value) {
            if($data['domain'] !== $key || empty($data['domain']))
            {
                $data['domain'] = $key;

                foreach($value as $elem => $each)
                {
                    $data['select'.$elem] = $each;
                }
                $this->curl->request(self::CMD_EMAIL_FORWARDER_V2, $data);
            }
        }
    }
}