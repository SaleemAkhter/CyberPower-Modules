<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 11, 2017)
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

namespace ModulesGarden\WordpressManager\App\Repositories;

use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use ModulesGarden\WordpressManager\App\Models\ProductSetting;
use ModulesGarden\WordpressManager\App\Helper\InstallationLimit;

/**
 * Description of HostingRepository
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class HostingRepository extends BaseRepository
{

    function __construct()
    {
        $this->model = new Hosting;
    }

    public function findEnabled($userId, $hostingId=null)
    {
        $h  = $this->model->getTable();
        $p  = with(new Product)->getTable();
        $ps = with(new ProductSetting)->getTable();
        $query = $this->model->select("{$h}.*", "{$p}.name")
                        ->rightJoin($ps, "{$h}.packageid", '=', "{$ps}.product_id")
                        ->leftJoin($p, "{$h}.packageid", '=', "{$p}.id")
                        ->where("{$h}.userid", $userId)
                        ->where("{$h}.domainstatus", 'Active')
                        ->where("{$ps}.enable", '1');
        if($hostingId){
            $query->where("{$h}.id", $hostingId);
        }
        return $query->get();
                        
    }

    public function hasEnabledProducts($userId)
    {
        $h  = $this->model->getTable();
        $p  = with(new Product)->getTable();
        $ps = with(new ProductSetting)->getTable();
        return $this->model->rightJoin($ps, "{$h}.packageid", '=', "{$ps}.product_id")
                        ->leftJoin($p, "{$h}.packageid", '=', "{$p}.id")
                        ->where("{$h}.userid", $userId)
                        ->where("{$h}.domainstatus", 'Active')
                        ->where("{$ps}.enable", '1')
                        ->count() >=1;
    }
    
    public function findEnabledAsArray($userId)
    {
        $data = [];
        $h    = $this->model->getTable();
        $this->model->select("{$h}.id", "{$h}.domain");
        foreach ($this->findEnabled($userId) as $hosting)
        {
            $data[$hosting->id] = $hosting->domain;
        }
        return $data;
    }
    
    public function findEnabledWithProduct($userId, $hostingId=null)
    {
        $data = [];
        $h    = $this->model->getTable();
        $p  = with(new Product)->getTable();
        $this->model->select("{$h}.id", "{$h}.domain", "{$p}.name");
        foreach ($this->findEnabled($userId, $hostingId) as $hosting)
        {
            if(!InstallationLimit::limitReached($hosting->id, $hosting->productSettings()->first()->getInstallationsLimit()))
            {
                $data[$hosting->id] = sprintf("%s - %s",$hosting->name , $hosting->domain);
            }
        }
        return $data;
    }
    
    
}
