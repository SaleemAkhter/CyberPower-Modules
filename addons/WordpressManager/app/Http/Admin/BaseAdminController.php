<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 15, 2018)
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

namespace ModulesGarden\WordpressManager\App\Http\Admin;

use \ModulesGarden\WordpressManager\App\Models\Installation;
use \ModulesGarden\WordpressManager\App\Helper\Wp;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
/**
 * Description of BaseAdminController
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
trait BaseAdminController
{
    private $installationId;
    private $userId;
    private $subModule;
    protected $installation;
    protected $hostingId;
    protected $hosting;

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

    public function reset(){
        unset($this->installation,$this->subModule);
        return $this;
    }
    
    /**
     * 
     * @return Installation
     */
    public function getInstallation()
    {
        if (!is_null($this->installation) )
        {
            return $this->installation;
        }
        return $this->installation = Installation::where("id", $this->installationId)
                                                   ->firstOrFail();
    }
    
    public function getHostingId()
    {
        return $this->hostingId;
    }

    public function setHostingId($hostingId)
    {
        $this->hostingId = $hostingId;
        return $this;
    }

    /**
     * @return Hosting
     */
    public function getHosting()
    {
        return $this->hosting = Hosting::where("id", $this->hostingId)
                                        ->firstOrFail();
    }

    /**
     * 
     * @return \ModulesGarden\WordpressManager\App\Interfaces\WordPressModuleInterface
     */
    public function subModule(){
        if (!is_null($this->subModule) )
        {
            return $this->subModule;
        }
        if($this->hosting){
            return $this->subModule = Wp::subModule($this->hosting);
        }
        return $this->subModule = Wp::subModule($this->getInstallation()->hosting);
    }
}
