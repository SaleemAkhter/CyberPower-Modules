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

use \ModulesGarden\WordpressManager\App\Models\ProductSetting;
use \ModulesGarden\WordpressManager as main;

/**
 * Description of ProductSettingRepository
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class ProductSettingRepository extends BaseRepository
{

    function __construct()
    {
        $this->model = (new ProductSetting);
    }

    /**
     * 
     * @param int $id
     * @return ProductSetting
     */
    public function forProductId($id)
    {
        if ($this->model->where('product_id', $id)->count())
        {
            return $this->model->where('product_id', $id)->first();
        }
        $model             = new ProductSetting;
        $model->product_id = $id;
        return $model;
    }

    /**
     * 
     * @param type $hostingId
     * @return boolean
     */
    public function isEnabled($hostingId)
    {
        $s = $this->model->getTable();
        $h = with(new main\Core\Models\Whmcs\Hosting)->getTable();
        return main\Core\Models\Whmcs\Hosting::leftJoin($s, "{$h}.packageid", '=', "{$s}.product_id")
                        ->where("{$h}.id", $hostingId)
                        ->where("{$h}.domainstatus","Active")     
                        ->where("{$s}.enable", 1)->count() > 0;
    }

    public function isEnabledForProduct($productId)
    {
        $s = $this->model->getTable();
        $h = with(new main\Core\Models\Whmcs\Hosting)->getTable();

        return main\Core\Models\Whmcs\Hosting::leftJoin($s, "{$h}.packageid", '=', "{$s}.product_id")
                ->where("{$h}.id", $hostingId)
                ->where("{$h}.domainstatus","Active")
                ->where("{$s}.enable", 1)->count() > 0;
    }

}
