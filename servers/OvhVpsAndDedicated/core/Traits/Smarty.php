<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ServiceLocator;

trait Smarty
{
    /**
     * @var null|\ModulesGarden\Servers\OvhVpsAndDedicated\Core\Http\View\Smarty
     */
    protected $smarty = null;

    public function loadSmarty()
    {
        if ($this->smarty === null)
        {
            $this->smarty = ServiceLocator::call('smarty');
            if (property_exists($this, 'lang') && method_exists($this, 'loadLang'))
            {
                $this->loadLang();
                $this->smarty->setLang($this->lang);
            }
            else
            {
                $lang = ServiceLocator::call('lang');
                $this->smarty->setLang($lang);
            }
        }
    }

    public function getSmarty()
    {
        if ($this->smarty === null)
        {
            $this->loadSmarty();
        }

        return $this->smarty;
    }
}
