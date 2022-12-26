<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits;

use function \ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;

/**
 * WhmcsParams related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait WhmcsParams
{
    /**
     *
     * @var type ModulesGarden\Servers\DirectAdminExtended\Core\Helper\WhmcsParams
     */
    protected $whmcsParams = null;

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
        if($key=='username' && Helper\isResellerLevel() && !defined("RESELLER_PAGE")){
            return $_SESSION['resellerloginas'][$this->getWhmcsParamByKey('serviceid')];
        }elseif($key=='username' && Helper\isAdminLevel()  && (Helper\isAdminLoggedInAsReseller()  || Helper\isAdminLoggedInAsUser())){
            return $_SESSION['adminloginas'][$this->getWhmcsParamByKey('serviceid')];
        }
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
