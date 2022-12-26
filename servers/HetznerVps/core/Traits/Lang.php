<?php

namespace ModulesGarden\Servers\HetznerVps\Core\Traits;

use ModulesGarden\Servers\HetznerVps\Core\ServiceLocator;

trait Lang
{
    /**
     * @var null|\ModulesGarden\Servers\HetznerVps\Core\Lang\Lang
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