<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\Traits;

use ModulesGarden\OvhVpsAndDedicated\Core\ServiceLocator;

trait Lang
{
    /**
     * @var null|\ModulesGarden\OvhVpsAndDedicated\Core\Lang\Lang
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