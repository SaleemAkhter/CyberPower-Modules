<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 16:37
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Interfaces\ResponseLoad;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;

class Ftp extends AbstractModel implements ResponseLoad
{
    public $domain;
    public $user;
    public $path;
    protected $password;
    protected $type;
    public $name;

    public function loadResponse($response, $function = null)
    {
        if($function === 'show')
        {
            $expodeFulluser = explode('@', $response['fulluser']);
            $data           = [
                'user'      => $expodeFulluser[0],
                'domain'    => $expodeFulluser[1],
                'path'      => $response['path'],
                'type'      => $response['type']
            ];
            $this->addResponseElement(new self($data));

            return $this;
        }

        foreach ($response as $user => $path)
        {
            $data = [
                'user'  => $user,
                'path'  => $path
            ];
            $this->addResponseElement(new self($data));
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     * @return Ftp
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Ftp
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return Ftp
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Ftp
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     * @return Ftp
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
}