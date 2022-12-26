<?php

namespace ModulesGarden\WordpressManager\Core\Traits;

use ModulesGarden\WordpressManager\Core\ServiceLocator;

trait Lang
{
    /**
     * @var null|\ModulesGarden\WordpressManager\Core\Lang\Lang
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