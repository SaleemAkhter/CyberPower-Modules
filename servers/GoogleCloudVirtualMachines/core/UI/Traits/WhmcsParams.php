<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits;

use function \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\di;

/**
 * WhmcsParams related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait WhmcsParams
{
    /**
     *
     * @var type ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\WhmcsParams
     */
    private $whmcsParams = null;

    public function initWhmcsParams()
    {
        if ($this->whmcsParams === null)
        {
            $this->whmcsParams = di('whmcsParams');
        }
    }

    protected function getWhmcsParamByKey($key, $default = false)
    {
        $this->initWhmcsParams();

        return $this->whmcsParams->getParamByKey($key, $default);
    }

    public function getWhmcsParamsByKeys(array $keys = [], $default = false)
    {
        $selectedParams = [];
        foreach ($keys as $key)
        {
            $selectedParams[$key] = $this->getWhmcsParamByKey($key, $default);
        }

        return $selectedParams;
    }

    protected function isWhmcsConfigOption($key)
    {
        $this->initWhmcsParams();
        return isset($this->whmcsParams->getParamByKey('configoptions')[$key]);
    }

    protected function getWhmcsConfigOption($key, $default = false)
    {
        $this->initWhmcsParams();
        if($this->isWhmcsConfigOption($key)){
            return $this->whmcsParams->getParamByKey('configoptions')[$key];
        }
        return $default;
    }

    protected function isWhmcsCustomField($key)
    {
        $this->initWhmcsParams();
        return isset($this->whmcsParams->getParamByKey('customfields')[$key]);
    }

    protected function getWhmcsCustomField($key, $default = false)
    {
        $this->initWhmcsParams();
        if($this->isWhmcsCustomField($key)){
            return $this->whmcsParams->getParamByKey('customfields')[$key];
        }
        return $default;
    }
}
