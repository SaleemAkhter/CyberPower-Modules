<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits;

/**
 * WhmcsParams related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait WhmcsParams
{

    protected $whmcsParams = [];

    public function setWhmcsParams(array $params)
    {
        if (!$this->whmcsParams)
        {
            $this->whmcsParams = $params;
        }

        return $this;
    }

    protected function getWhmcsParamByKey($key, $default = false)
    {
        return isset($this->whmcsParams[$key]) ? $this->whmcsParams[$key] : $default;
    }

}
