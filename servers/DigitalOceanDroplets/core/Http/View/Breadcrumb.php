<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\View;

/**
 * Description of Breadcrumb
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Breadcrumb
{
    /**
     * @var array
     */
    protected $data = [];

    public function __construct()
    {
        
    }

    public function load(array $menu = [], $controller = null, $action = null)
    {
        if (empty($controller))
        {
            $controller = key($menu);
        }
        if ($controller)
        {
            $this->data[0] = [
                'name' => $controller
                , 'url'  => $menu[$controller]['url']
                , 'icon' => $menu[$controller]['icon']
            ];
            if ($action)
            {
                $this->data[1] = [
                    'name' => $action,
                    'url'  => $menu[$controller]['submenu'][$action]['url'],
                    'icon' => $menu[$controller]['submenu'][$action]['icon']
                ];
            }
        }
        return $this;
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->data;
    }
}
