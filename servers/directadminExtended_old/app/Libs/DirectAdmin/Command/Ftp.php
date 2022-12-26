<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Ftp extends AbstractCommand
{
    const CMD_FTP       = 'CMD_API_FTP';
    const CMD_FTP_V2    = 'CMD_FTP';
    const CMD_FTP_SHOW  = 'CMD_API_FTP_SHOW';

    /**
     * get ftp accounts list
     *
     * @param Models\Command\Ftp $ftp
     * @return mixed
     */
    public function lists(Models\Command\Ftp $ftp)
    {
        $response = $this->curl->request(self::CMD_FTP_V2, [
            'domain' => $ftp->getDomain(),
            'json' => 'yes',
            'full_json' => 'yes'
        ]);
        return $this->loadResponse(new Models\Command\Ftp(), $response->users);
    }

    /**
     * create ftp account
     *
     * @param Models\Command\Ftp $ftp
     * @return mixed
     */
    public function create(Models\Command\Ftp $ftp)
    {
        return $this->curl->request(self::CMD_FTP, [
            'action'        => __FUNCTION__,
            'domain'        => $ftp->getDomain(),
            'user'          => $ftp->getUser(),
            'type'          => $ftp->getType(),
            'passwd'        => $ftp->getPassword(),
            'passwd2'       => $ftp->getPassword(),
            'custom_val'    => $ftp->getPath(),
        ]);
    }

    /**
     * delete ftp account
     *
     * @param Models\Command\Ftp $ftp
     * @return mixed
     */
    public function delete(Models\Command\Ftp $ftp)
    {
        return $this->curl->request(self::CMD_FTP, [
            'action'    => __FUNCTION__,
            'domain'    => $ftp->getDomain(),
            'select0'   => $ftp->getUser()
        ]);
    }

    /**
     * modify ftp account
     *
     * @param Models\Command\Ftp $ftp
     * @return mixed
     */
    public function modify(Models\Command\Ftp $ftp)
    {
        return $this->curl->request(self::CMD_FTP, [
            'action'        => __FUNCTION__,
            'domain'        => $ftp->getDomain(),
            'user'          => $ftp->getUser(),
            'type'          => $ftp->getType(),
            'passwd'        => $ftp->getPassword(),
            'passwd2'       => $ftp->getPassword(),
            'custom_val'    => $ftp->getPath(),
        ]);
    }

    /**
     * show ftp acccount
     *
     * @param Models\Command\Ftp $ftp
     * @return mixed
     */
    public function show(Models\Command\Ftp $ftp)
    {
        $response = $this->curl->request(self::CMD_FTP_SHOW, [
            'domain'        => $ftp->getDomain(),
            'user'          => $ftp->getUser(),
        ]);

        return $this->loadResponse(new Models\Command\Ftp(), $response, __FUNCTION__);
    }

    public function suspendMany(array $suspendData)
    {
        foreach($suspendData as $key => $value) {
            $data = [
                'json'    => 'yes',
                'action' => 'delete',
                'suspend' => 'yes',
            ];
            if($data['domain'] !== $key || empty($data['domain']))
            {
                $data['domain'] = $key;

                foreach($value as $elem => $each)
                {
                    $data['select'.$elem] = $each;
                }
                $this->curl->request(self::CMD_FTP_V2, $data);
            }
        }
    }

    public function unsuspendMany(array $suspendData)
    {
        foreach($suspendData as $key => $value)
        {
            $data = [
                'json'    => 'yes',
                'action' => 'delete',
                'unsuspend' => 'yes',
            ];
            if($data['domain'] !== $key || empty($data['domain']))
            {
                $data['domain'] = $key;

                foreach($value as $elem => $each)
                {
                    $data['select'.$elem] = $each;
                }
                $this->curl->request(self::CMD_FTP_V2, $data);
            }
        }
    }

    public function deleteMany(array $deleteData)
    {
        $data = [
            'json'    => 'yes',
            'action' => 'delete',
            'delete' => 'yes',
        ];

        foreach($deleteData as $key => $value) {
            if($data['domain'] !== $key || empty($data['domain']))
            {
                $data['domain'] = $key;

                foreach($value as $elem => $each)
                {
                    $data['select'.$elem] = $each;
                }
                $this->curl->request(self::CMD_FTP_V2, $data);
            }
        }
    }
}