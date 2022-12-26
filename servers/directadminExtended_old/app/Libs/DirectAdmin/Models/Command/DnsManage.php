<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command;


use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Interfaces\ResponseLoad;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;

class DnsManage extends AbstractModel implements ResponseLoad
{

    protected $domain;
    protected $type;
    protected $name;
    protected $value;
    protected $ttl;
    protected $oldValue;
    protected $oldName;
    protected $mxValue;

    public function loadResponse($response, $function = null)
    {
        if ($function === 'listRecords')
        {
            $ttl = $response->ttl_value;

            foreach($response->records as $elem => $value)
            {
                $value->ttl = $ttl;
                $this->addResponseElement(new self($value));
            }
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
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function getOldValue()
    {
        return $this->oldValue;
    }

    public function setOldValue($value)
    {
        $this->oldValue = $value;
        return $this;
    }
    public function getOldName()
    {
        return $this->oldName;
    }

    public function setOldName($value)
    {
        $this->oldName = $value;
        return $this;
    }
    public function getMxValue()
    {
        return $this->mxValue;
    }

    public function setMxValue($value)
    {
        $this->mxValue = $value;
        return $this;
    }

    public function getTtl()
    {
        return $this->ttl;
    }

    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
        return $this;
    }
}