<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 14:36
 */
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Connection;


class Curl
{
    protected $hostname;
    protected $ip;
    protected $port;
    protected $ssl = false;
    protected $username;
    protected $password;
    protected $runasadmin;
    /**
     * @return mixed
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param mixed $hostname
     * @return Curl
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     * @return Curl
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     * @return Curl
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSsl()
    {
        return $this->ssl;
    }

    /**
     * @param mixed $ssl
     * @return Curl
     */
    public function setSsl($ssl)
    {
        $this->ssl = $ssl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return Curl
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getRunAsAdmin()
    {
        return $this->runasadmin;
    }

    /**
     * @param mixed $username
     * @return Curl
     */
    public function setRunAsAdmin($runasadmin)
    {
        $this->runasadmin = $runasadmin;
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
     * @return Curl
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

}
