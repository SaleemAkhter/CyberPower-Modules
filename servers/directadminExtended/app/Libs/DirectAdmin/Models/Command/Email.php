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

class Email extends AbstractModel implements ResponseLoad
{
    public $domain;
    public $user;
    public $newUser;
    public $quota;
    public $sent;
    public $usage;
    public $password;
    public $limit;
    public $name;
    public $oldPassword;
    public $daSwitcher;
    public $ftpSwitcher;
    public $dbSwitcher;


    public function loadResponse($response, $function = null)
    {
        foreach ($response as $user => $details)
        {
            parse_str($details,$detailsArray);

            $data = array_merge($detailsArray, [
                'user'      => $user
            ]);
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
     * @return Email
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
     * @return Email
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getNewUser()
    {
        return $this->newUser;
    }

    /**
     * @param mixed $user
     * @return Email
     */
    public function setNewUser($user)
    {
        $this->newUser = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuota()
    {
        return $this->quota;
    }

    /**
     * @param mixed $quota
     * @return Email
     */
    public function setQuota($quota)
    {
        $this->quota = $quota;
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
     * @return Email
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     * @return Email
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * @param mixed $sent
     * @return Email
     */
    public function setSent($sent)
    {
        $this->sent = $sent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsage()
    {
        return $this->usage;
    }

    /**
     * @param mixed $usage
     * @return Email
     */
    public function setUsage($usage)
    {
        $this->usage = $usage;
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

    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
        return $this;
    }

    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    public function setDaSwitcher($daSwitcher)
    {
        $this->daSwitcher = $daSwitcher;
        return $this;
    }

    public function getDaSwitcher()
    {
        return $this->daSwitcher;
    }

    public function setFtpSwitcher($ftpSwitcher)
    {
        $this->ftpSwitcher = $ftpSwitcher;
        return $this;
    }

    public function getFtpSwitcher()
    {
        return $this->ftpSwitcher;
    }

    public function setDbSwitcher($dbSwitcher)
    {
        $this->dbSwitcher = $dbSwitcher;
        return $this;
    }

    public function getDbSwitcher()
    {
        return $this->dbSwitcher;
    }

}
