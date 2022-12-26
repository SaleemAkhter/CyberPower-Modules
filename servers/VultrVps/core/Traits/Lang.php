<?php

namespace ModulesGarden\Servers\VultrVps\Core\Traits;

use ModulesGarden\Servers\VultrVps\Core\ServiceLocator;

trait Lang
{
    /**
     * @var null|\ModulesGarden\Servers\VultrVps\Core\Lang\Lang
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