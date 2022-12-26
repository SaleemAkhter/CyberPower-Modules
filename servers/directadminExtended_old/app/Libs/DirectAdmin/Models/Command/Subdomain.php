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

class Subdomain extends AbstractModel implements ResponseLoad
{
    public $name;
    public $domain;
    public $type;
    public $subdomain;
    public $logs = [];
    protected $contents;

    public function loadResponse($response, $function = null)
    {
        if(isset($response['list']))
        {
            foreach ($response['list'] as $domain)
            {
                $self = new self(['name' => $domain]);
                $this->addResponseElement($self);
            }
        }

        return $this;
    }

    public function getLogs()
    {
        return $this->logs;
    }

    public function addLog($log)
    {
        $this->logs[] = $log;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Subdomain
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return Subdomain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * @param mixed $domain
     * @return Subdomain
     */
    public function setSubdomain($domain)
    {
        $this->subdomain = $domain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param mixed $contents
     * @return Subdomain
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
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
     * @param mixed $contents
     * @return Subdomain
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

}