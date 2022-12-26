<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

class DirectAdminWHMCS
{

    public static function load()
    {
        if (!function_exists('directadmin_configoptions'))
        {
            $maindir  = substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), 'modules' . DS . 'servers'));
            $filename = $maindir . 'modules' . DS . 'servers' . DS . 'directadmin' . DS . 'directadmin.php';
            if (file_exists($filename))
            {
                require_once($filename);
            }
            else
            {
                die('Unable to find DirectAdmin main module!');
            }
        }
    }

    public static function getIP($params)
    {
        return directadmin_req('CMD_API_SHOW_RESELLER_IPS'  , ['action' => 'all'], $params);
    }
}
