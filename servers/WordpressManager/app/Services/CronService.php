<?php

namespace ModulesGarden\WordpressManager\App\Services;

use \ModulesGarden\WordpressManager as main;

class CronService
{
    /**
     * 
     * @return main\Core\Models\Whmcs\Product[]
     */
    public static function getProductsEnabled()
    {
        return main\Core\Models\Whmcs\Product::whereIn('id', function ($query) {
            $query->select('product_id')
                ->from(with(new main\App\Models\ProductSetting)->getTable())
                ->where('enable', '1');
        })->get();
    }
}
