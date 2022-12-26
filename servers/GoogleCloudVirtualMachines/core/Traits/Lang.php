<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Traits;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\ServiceLocator;

trait Lang
{
    /**
     * @var null|\ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Lang\Lang
     */
    protected $lang = null;

    public function loadLang()
    {
        if ($this->lang === null)
        {
            $this->lang = ServiceLocator::call('lang');
        }
    }
}