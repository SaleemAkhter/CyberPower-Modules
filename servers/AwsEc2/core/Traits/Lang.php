<?php

namespace ModulesGarden\Servers\AwsEc2\Core\Traits;

use ModulesGarden\Servers\AwsEc2\Core\ServiceLocator;

trait Lang
{
    /**
     * @var null|\ModulesGarden\Servers\AwsEc2\Core\Lang\Lang
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