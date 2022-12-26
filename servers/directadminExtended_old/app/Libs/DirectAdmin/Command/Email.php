<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Email extends AbstractCommand
{
    const CMD_POP  = 'CMD_API_POP';
    const CMD_POP_V2  = 'CMD_EMAIL_POP';
    const CMD_PASSWD  = 'CMD_PASSWD';

    /**
     * get emails list
     *
     * @param Models\Command\Email $email
     * @return mixed
     */
    public function lists(Models\Command\Email $email)
    {
        return $this->curl->request(self::CMD_POP_V2, [],[
            'domain'    =>  $email->getDomain(),
            'json'      => 'yes',
            'bytes'     => 'yes',
            'page'      => '1',
            'ipp'       => 500
        ]);
    }

    /**
     * create email
     *
     * @param Models\Command\Email $email
     * @return mixed
     */
    public function create(Models\Command\Email $email)
    {
        return $this->curl->request(self::CMD_POP, [
            'action'    => __FUNCTION__,
            'domain'    => $email->getDomain(),
            'user'      => $email->getUser(),
            'passwd'    => $email->getPassword(),
            'passwd2'   => $email->getPassword(),
            'quota'     => $email->getQuota(),
            'limit'     => $email->getLimit()
        ]);
    }

    /**
     * delete email
     *
     * @param Models\Command\Email $email
     * @return mixed
     */
    public function delete(Models\Command\Email $email)
    {
        return $this->curl->request(self::CMD_POP, [
            'action'    => __FUNCTION__,
            'domain'    => $email->getDomain(),
            'user'      => $email->getUser()
        ]);
    }

    /**
     * modify email
     *
     * @param Models\Command\Email $email
     * @return mixed
     */
    public function modify(Models\Command\Email $email)
    {
        return $this->curl->request(self::CMD_POP_V2, [
            'action'    => __FUNCTION__,
            'domain'    => $email->getDomain(),
            'json'      => 'yes',
            'passwd'    => html_entity_decode($email->getPassword(), ENT_QUOTES),
            'passwd2'   => html_entity_decode($email->getPassword(), ENT_QUOTES),
            'user'      => $email->getUser(),
            'newuser'   => $email->getNewUser(),
            'quota'     => $email->getQuota(),
            'limit'     => $email->getLimit()
        ], [], false, true);
    }

    public function deleteMany(array $deleteData)
    {
        $data = [
            'json'    => 'yes',
            'action' => 'delete',
            'delete' => 'yes',
            'clean_forwarders' => 'yes'
        ];

        foreach($deleteData as $key => $value) {
            if($data['domain'] !== $key || empty($data['domain']))
            {
                $data['domain'] = $key;
                foreach($value as $elem => $each)
                {
                    $data['select'.$elem] = $each;
                }
                $this->curl->request(self::CMD_POP_V2, $data);
            }
        }
    }

    public function changePassword(Models\Command\Email $email)
    {
        $data = [
            'oldpass' => $email->getOldPassword(),
            'passwd' => html_entity_decode($email->getPassword(), ENT_QUOTES),
            'passwd2' => html_entity_decode($email->getPassword(), ENT_QUOTES),
            'system' => $email->getDaSwitcher(),
            'ftp' => $email->getFtpSwitcher(),
            'database' => $email->getDbSwitcher(),
            'json' => 'yes',
            'options' => 'yes'
        ];

        $response = $this->curl->request(self::CMD_PASSWD, $data);

        return $response;
    }
}