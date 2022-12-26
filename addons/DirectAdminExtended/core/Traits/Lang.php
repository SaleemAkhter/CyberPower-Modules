<?php

namespace ModulesGarden\DirectAdminExtended\Core\Traits;

use ModulesGarden\DirectAdminExtended\Core\ServiceLocator;

trait Lang
{
    /**
     * @var null|\ModulesGarden\DirectAdminExtended\Core\Lang\Lang
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