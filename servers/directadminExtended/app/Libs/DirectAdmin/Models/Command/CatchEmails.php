<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command;


use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;

class CatchEmails extends AbstractModel
{

    protected $email;
    protected $domain;
    protected $option;

    public function getOption()
    {
        return $this->option;
    }

    public function setOption($option)
    {
        $this->option = $option;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }
}