<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 5, 2018)
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

namespace ModulesGarden\WordpressManager\App\Http\Client;

use \ModulesGarden\WordpressManager\App\Models\Installation;
use \ModulesGarden\WordpressManager\App\Helper\Wp;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use \ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
use  \ModulesGarden\WordpressManager as main;

/**
 * Description of BaseController
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
trait BaseClientController
{
    protected $installationId;
    protected $userId;
    protected $subModule;
    /**
     *
     * @var Installation
     */
    protected $installation;
    protected $hostingId;
    protected $hosting;
    protected $pluginPackage;
    protected $customPlugin;
    private $pluginPackageId;
    private $customPluginId;

    public function getInstallationId()
    {
        return $this->installationId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setInstallationId($installationId)
    {
        $this->installationId = $installationId;
        return $this;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function reset()
    {
        unset($this->installation, $this->subModule, $this->hostingId);
        return $this;
    }
    
    public function setInstallation($installation)
    {
        $this->installation = $installation;
        return $this;
    }

        /**
     * 
     * @return Installation
     */
    public function getInstallation()
    {
        if (!is_null($this->installation))
        {
            return $this->installation;
        }
        return $this->installation = Installation::where("user_id", $this->userId)
                ->where("id", $this->installationId)
                ->firstOrFail();
    }

    public function getHostingId()
    {
        return $this->hostingId;
    }

    public function setHostingId($hostingId)
    {
        if(is_null($hostingId) || !is_numeric($hostingId)){
             throw new \Exception("Hosting ID cannot be empty");    
        }
        $this->hostingId = $hostingId;
        return $this;
    }

    /**
     * @return Hosting
     */
    public function getHosting()
    {
        return $this->hosting = Hosting::where("userid", $this->userId)
                ->where("id", $this->hostingId)
                ->firstOrFail();
    }

    /**
     * 
     * @return \ModulesGarden\WordpressManager\App\Interfaces\WordPressModuleInterface
     */
    public function subModule()
    {
        if (!is_null($this->subModule))
        {
            return $this->subModule;
        }
        if ($this->hosting || $this->hostingId)
        {
            return $this->subModule = Wp::subModule($this->getHosting());
        }
        return $this->subModule = Wp::subModule($this->getInstallation()->hosting);
    }

    public function getPluginPackageId()
    {
        return $this->pluginPackageId;
    }

    public function setPluginPackageId($pluginPackageId)
    {
        $this->pluginPackageId = $pluginPackageId;
        return $this;
    }

    /**
     * 
     * @return main\App\Models\PluginPackage
     * @throws \Exception
     */
    public function getPluginPackage()
    {
        if (!is_null($this->pluginPackage))
        {
            return $this->pluginPackage;
        }
        if(!in_array($this->getPluginPackageId(), $this->getInstallation()->hosting->productSettings->getPluginPackages())){
            throw new \Exception(sprintf("No access to plugin package: %s",$this->getPluginPackageId()));
        }
        return $this->pluginPackage = main\App\Models\PluginPackage::where("id", $this->getPluginPackageId())
                                          ->where("enable",'1')->firstOrFail();
    }
     public function getCustomPluginId()
    {
        return $this->customPluginId;
    }

    public function setCustomPluginId($customPluginId)
    {
        $this->customPluginId = $customPluginId;
        return $this;
    }

    /**
     *
     * @return main\App\Models\PluginPackage
     * @throws \Exception
     */
    public function getCustomPlugin()
    {
        if (!is_null($this->customPlugin))
        {
            return $this->customPlugin;
        }
        if(!in_array($this->getCustomPluginId(), $this->getInstallation()->hosting->productSettings->getCustomPlugins())){
            throw new \Exception(sprintf("No access to plugin package: %s",$this->getCustomPluginId()));
        }
        return $this->customPlugin = main\App\Models\CustomPlugin::where("id", $this->getCustomPluginId())
                                          ->where("enable",'1')->firstOrFail();
    }
}
