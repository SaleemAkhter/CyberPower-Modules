<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Helper\Download\Download;

class Database extends AbstractCommand
{
    const CMD_DATABASE      = 'CMD_API_DATABASES';
    const CMD_DB      = 'CMD_DB';
    const CMD_DB_USER       = 'CMD_API_DB_USER';
    const CMD_DB_USER_PRIVS = 'CMD_API_DB_USER_PRIVS';
    /**
     * list databases
     *
     * @return mixed
     */
    public function lists()
    {
        $response = $this->curl->request(self::CMD_DATABASE);

        return $this->loadResponse(new Models\Command\Database(), $response);
    }

    /**
     * list database users
     *
     * @param Models\Command\Database $database
     * @return mixed
     */
    public function users(Models\Command\Database $database)
    {
        $response = $this->curl->request(self::CMD_DATABASE, [
            'action'    => __FUNCTION__,
            'db'        => $database->getName()
        ]);

        return $this->loadResponse(new Models\Command\Database(), $response, __FUNCTION__);
    }

    /**
     * create database
     *
     * @param Models\Command\Database $database
     * @return mixed
     */
    public function create(Models\Command\Database $database)
    {
        return $this->curl->request(self::CMD_DATABASE, [
            'action'    => __FUNCTION__,
            'name'      => $database->getName(),
            'user'      => $database->getUser(),
            'passwd'    => $database->getPassword(),
            'passwd2'   => $database->getPassword()
        ]);
    }

    /**
     * create database user
     *
     * @param Models\Command\Database $database
     * @return mixed
     */
    public function createUser(Models\Command\Database $database)
    {
        return $this->curl->request(self::CMD_DB_USER, [
            'action'    => 'create',
            'name'      => $database->getName(),
            'user'      => $database->getUser(),
            'passwd'    => $database->getPassword(),
            'passwd2'   => $database->getPassword()
        ]);
    }

    /**
     * delete database
     *
     * @param Models\Command\Database $database
     * @return mixed
     */
    public function delete(Models\Command\Database $database)
    {
        return $this->curl->request(self::CMD_DATABASE, [
            'action'    => __FUNCTION__,
            'select0'   => $database->getName()
        ]);
    }

    /**
     * delete database user
     *
     * @param Models\Command\Database $database
     * @return mixed
     */
    public function deleteUser(Models\Command\Database $database)
    {
        return $this->curl->request(self::CMD_DB_USER, [
            'action'    => 'delete',
            'name'      => $database->getName(),
            'select0'   => $database->getUser()
        ]);
    }

    /**
     * delete many databases
     *
     * @param array of Models\Command\Database
     * @return mixed
     */
    public function deleteMany(array $databases)
    {
        $data = [];
        foreach ($databases as $key => $db)
        {
            $data['select' . $key] = $db->getName();
        }
        return $this->curl->request(self::CMD_DATABASE, array_merge([
            'action'    => 'delete'
        ], $data));
    }

    public function deleteManyUsers(array $users)
    {

        foreach($users as $database => $userList)
        {
            $data = [];
            $data['name'] = $database;
            foreach ($userList as $key => $user)
            {
                $data['select' . $key] = $user;
            }

            $this->curl->request(self::CMD_DB_USER, array_merge([
                'action'    => 'delete'
            ], $data));

        }
    }

    /**
     * @param $domain
     * @param $database
     * @param $user
     * @return mixed
     */
    public function userPrivileges($domain, $database, $user)
    {
        return $this->curl->request(self::CMD_DB_USER_PRIVS, [], [
            'domain'    => $domain,
            'name'      => $database,
            'user'      => $user
        ]);
    }

    public function updateUserPrivileges($domain, $database, $user, $privileges)
    {
        return $this->curl->request(self::CMD_DB_USER_PRIVS, array_merge([
            'domain'    => $domain,
            'name'      => $database,
            'user'      => $user
        ], $privileges));
    }

    public function changeUserPassword(Models\Command\Database $database)
    {
        return $this->curl->request(self::CMD_DATABASE, [
            'action'    => 'modifyuser',
            'domain'    => $database->getDomain(),
            'name'      => $database->getName(),
            'user'      => $database->getUser(),
            'passwd'    => $database->getPassword(),
            'passwd2'   => $database->getPassword()
        ]);
    }

    public function download(Models\Command\Database $database)
    {
        $fileName = $database->getName(). '.gz';
        $fileContent = $this->curl->customRequest(self::CMD_DB.'/' . $fileName);
        Download::prepare($fileName, $fileContent);
    }
}