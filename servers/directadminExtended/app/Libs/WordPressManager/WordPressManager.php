<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\WordPressManager;


use ModulesGarden\Servers\DirectAdminExtended\App\Models\AddonModule;
use ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;

class WordPressManager
{

    public static function isActive()
    {
        $model = new AddonModule();
        $isEnabled = $model->where('module', '=' , 'WordpressManager')->first();

        return is_null($isEnabled) ? false : true;
    }

    /**
     *
     * @param type $hid
     * @return boolean
     */
    public static function activeForHosting($hid = null)
    {
        if(!class_exists("\\ModulesGarden\\WordpressManager\\App\\Repositories\\ProductSettingRepository"))
        {
            return false;
        }

        $helper = new ProductSettingRepository();
        return $helper->isEnabled($hid);
    }

    /**
     * @return bool
     */
    public static function getVersion()
    {
        if (function_exists('\ModulesGarden\WordpressManager\Core\Helper\di') && class_exists('\ModulesGarden\WordpressManager\Core\Configuration\Addon'))
        {
            $version = \ModulesGarden\WordpressManager\Core\Helper\di('\ModulesGarden\WordpressManager\Core\Configuration\Addon')->getConfig('version', 'default');
            return $version;
        }

        return false;
    }

}