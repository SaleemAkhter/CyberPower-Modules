<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 12, 2017)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\Helper;

use ModulesGarden\WordpressManager\App\Database\PdoConnection;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\Core\ModuleConstants;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Product;

/**
 * Description of SubModuleFactory
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class Wp
{

    /**
     * 
     * @param Hosting $hosting
     * @param type $params
     * @return \ModulesGarden\WordpressManager\App\Interfaces\WordPressModuleInterface
     * @throws \Exception
     */
    public static function subModule(Hosting $hosting, $params = [])
    {
        if (!$params)
        {
            $params = WhmcsHelper::getParams($hosting->id);
            if(empty($params['serverhostname']) || empty($params['serverip'])){
                //Swap connection in WHMCS
                $capsule = PdoConnection::getCapsule();
                \DI::make("db")->setPdo($capsule->getConnection()->getPdo());
                \DI::make("mysqlCompat")->setPdo($capsule->getConnection()->getPdo());
                $capsule->getConnection()->disableQueryLog();
                $params = WhmcsHelper::getParams($hosting->id);
            }
        }
        $className = ucfirst($hosting->product->servertype);
        if (!file_exists(ModuleConstants::getFullPath('app', 'Modules') . DS . "{$className}.php"))
        {
            throw new \Exception(sprintf("Wordpress Manager can not find submodule '%s' ", $className));
        }
        $fullClassName = "\\ModulesGarden\\WordpressManager\\App\\Modules\\" . $className;
        /* @var  \ModulesGarden\WordpressManager\App\Interfaces\WordPressModuleInterface  $submodule */
        $submodule     = new $fullClassName();
        $submodule->setParams($params);
        if($hosting->productSettings){
            $submodule->setDebugMode($hosting->productSettings->isDebugMode());
        }
        return $submodule;
    }
    
    public static function subModuleForProduct(Product $product, $params){
        
        $className = ucfirst($product->servertype);
        if (!file_exists(ModuleConstants::getFullPath('app', 'Modules') . DS . "{$className}.php"))
        {
            throw new \Exception(sprintf("Wordpress Manager can not find submodule '%s' ", $className));
        }
        $fullClassName = "\\ModulesGarden\\WordpressManager\\App\\Modules\\" . $className;
        /* @var  \ModulesGarden\WordpressManager\App\Interfaces\WordPressModuleInterface  $submodule */
        $submodule     = new $fullClassName();
        $submodule->setParams($params);
        if($product->setting){
            $submodule->setDebugMode($product->setting->isDebugMode());
        }
        return $submodule;
    }
}
