<?php

namespace ModulesGarden\DirectAdminExtended\Core\UI\Traits;

use function \ModulesGarden\DirectAdminExtended\Core\Helper\di;

/**
 * WhmcsParams related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait WhmcsParams
{
    /**
     *
     * @var type ModulesGarden\DirectAdminExtended\Core\Helper\WhmcsParams
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
}
