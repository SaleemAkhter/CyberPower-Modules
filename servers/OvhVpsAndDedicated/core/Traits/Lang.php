<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ServiceLocator;

trait Lang
{
    /**
     * @var null|\ModulesGarden\Servers\OvhVpsAndDedicated\Core\Lang\Lang
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