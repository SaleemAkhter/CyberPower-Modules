<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\Traits;

use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;

trait Lang
{
    /**
     * @var null|\ModulesGarden\Servers\DirectAdminExtended\Core\Lang\Lang
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