<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 7, 2017)
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

namespace ModulesGarden\WordpressManager\App\UI\Dashboard;

use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Helper\WhmcsHelper;
use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer;
use ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\Models\Whmcs\Client;
use \ModulesGarden\WordpressManager\App\Models\ProductSetting;
use \ModulesGarden\WordpressManager\Core\ModuleConstants;
use \ModulesGarden\WordpressManager\App\Models\PluginPackage;
use \ModulesGarden\WordpressManager\App\Models\ThemePackage;
use \ModulesGarden\WordpressManager\App\Models\InstanceImage;
/* * gb
 * Description of InstallationPage
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */

class DashboardPage extends BaseContainer implements AdminArea
{

    use RequestObjectHandler;
    protected $id    = 'dashboard';
    protected $name  = 'dashboard-name';
    protected $title = 'dashboard-title';

    public function initContent() 
    {
        $this->customTplVars['installations']['total'] = Installation::count();
        $this->customTplVars['clients']['total'] = Client::whereIn('id', function ($query){
            $query->select('user_id')
                  ->from((new Installation)->getTable());
        })->count();
        $this->customTplVars['products']['total'] = ProductSetting::where("enable", 1)->count();
        $this->customTplVars['cron']['path']= ModuleConstants::getFullPath("cron","cron.php")." Synchronize";
        $this->customTplVars['cron']['path2']= ModuleConstants::getFullPath("cron","cron.php")." speedtest";
        $this->customTplVars['pluginPackage']['total'] = PluginPackage::where("enable", 1)->count();
        $this->customTplVars['themePackage']['total']  = ThemePackage::where("enable", 1)->count();
        $this->customTplVars['instanceImage']['total'] = InstanceImage::where("enable", 1)->count();
    }
}
