<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Autoresponder extends AbstractCommand
{
    const CMD_EMAIL_AUTORESPONDER      = 'CMD_API_EMAIL_AUTORESPONDER';
    const CMD_EMAIL_AUTORESPONDER_V2      = 'CMD_EMAIL_AUTORESPONDER';
    const CMD_EMAIL_AUTORESPONDER_VIEW = 'CMD_API_EMAIL_AUTORESPONDER_MODIFY';

    /**
     * get list of autoresponders instances
     *
     * @param Models\Command\Autoresponder $autoresponder
     * @return mixed
     */
    public function lists(Models\Command\Autoresponder $autoresponder)
    {
        $response = $this->curl->request(self::CMD_EMAIL_AUTORESPONDER, [
            'domain' => $autoresponder->getDomain()
        ]);

        return $this->loadResponse(new Models\Command\Autoresponder(), $response);
    }

    /**
     * create autoresponder
     *
     * @param Models\Command\Autoresponder $autoresponder
     * @return mixed
     */
    public function create(Models\Command\Autoresponder $autoresponder)
    {
        return $this->curl->request(self::CMD_EMAIL_AUTORESPONDER, [
            'action'    => __FUNCTION__,
            'domain'    => $autoresponder->getDomain(),
            'user'      => $autoresponder->getUser(),
            'text'      => $autoresponder->getText(),
            'cc'        => $autoresponder->getCc(),
            'email'     => $autoresponder->getEmail(),
            'reply_encoding' => 'UTF-8'
        ], null, true);
    }

    /**
     * delete autoresponder
     *
     * @param Models\Command\Autoresponder $autoresponder
     * @return mixed
     */
    public function delete(Models\Command\Autoresponder $autoresponder)
    {
        return $this->curl->request(self::CMD_EMAIL_AUTORESPONDER, [
            'action'    => __FUNCTION__,
            'domain'    => $autoresponder->getDomain(),
            'select0'   => $autoresponder->getUser()
        ]);
    }

    /**
     *  modify autoresponder
     *
     * @param Models\Command\Autoresponder $autoresponder
     * @return mixed
     */
    public function modify(Models\Command\Autoresponder $autoresponder)
    {
        return $this->curl->request(self::CMD_EMAIL_AUTORESPONDER, [
            'action'    => __FUNCTION__,
            'domain'    => $autoresponder->getDomain(),
            'user'      => $autoresponder->getUser(),
            'text'      => $autoresponder->getText(),
            'cc'        => $autoresponder->getCc(),
            'email'     => $autoresponder->getEmail()
        ], null, true);
    }

    /**
     * view autoresponder
     *
     * @param Models\Command\Autoresponder $autoresponder
     * @return mixed
     */
    public function view(Models\Command\Autoresponder $autoresponder)
    {
        $response =  $this->curl->request(self::CMD_EMAIL_AUTORESPONDER_VIEW, [
            'domain' => $autoresponder->getDomain(),
            'user'   => $autoresponder->getUser()
        ]);

        return $this->loadResponse(new Models\Command\Autoresponder(), $response, __FUNCTION__);
    }

    public function deleteMany(array $deleteData)
    {
        $data = [
            'json'    => 'yes',
            'action' => 'delete',
            'domain' => null
        ];

        foreach($deleteData as $key => $value)
        {
            if($data['domain'] !== $key || empty($data['domain']))
            {
                $data['domain'] = $key;
                foreach($value as $elem => $each)
                {
                    $data['select'.$elem] = $each;
                }
               $this->curl->request(self::CMD_EMAIL_AUTORESPONDER_V2, [], $data);
            }
        }
    }
}