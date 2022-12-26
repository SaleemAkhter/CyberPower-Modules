<?php

namespace ModulesGarden\WordpressManager\Core\Hook;

use ModulesGarden\WordpressManager\Core\FileReader\Reader;
use ModulesGarden\WordpressManager\Core\ModuleConstants;

/**
 * Description of Config
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Config
{
    /**
     * @var type
     */
    protected $data = [];

    public function __construct()
    {
        $this->data = Reader::read(ModuleConstants::getDevConfigDir() . DS . 'hooks.yml')->get('name', []);
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function checkHook($name)
    {
        if (isset($this->data) && count($this->data) != 0)
        {
            return (bool) array_get($this->data, $name, true);
        }
        return true;
    }
}
