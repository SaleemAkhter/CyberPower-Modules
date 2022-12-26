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

namespace ModulesGarden\WordpressManager\App\Models\Whmcs;

use ModulesGarden\WordpressManager\Core;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use ModulesGarden\WordpressManager\App\Models\ProductSetting;

/**
 * Description of Hosting
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 * @property int $id
 * @property int $userid
 * @property int $orderid
 * @property int $packageid
 * @property int $server
 * @property string $regdate
 * @property string $domain
 * @property string $paymentmethod
 * @property float $firstpaymentamount
 * @property float $amount
 * @property string $billingcycle
 * @property string $nextduedate
 * @property string $nextinvoicedate
 * @property string $termination_date
 * @property string $completed_date
 * @property string $domainstatus
 * @property string $username
 * @property string $password
 * @property string $notes
 * @property string $subscriptionid
 * @property int $promoid
 * @property string $suspendreason
 * @property int $overideautosuspend
 * @property string $overidesuspenduntil
 * @property string $dedicatedip
 * @property string $assignedips
 * @property string $ns1
 * @property string $ns2
 * @property int $diskusage
 * @property int $disklimit
 * @property int $bwusage
 * @property int $bwlimit
 * @property string $lastupdate
 * @property string $created_at
 * @property string $updated_at
 * @property Product $product
 * @property ProductSetting $productSettings
 * @method Hosting active()
 * @method Hosting ofUserId($userId)
 * @method Hosting productEnable()
 * @method static Hosting ofUserAndServerTypePlesk($userId)
 */
class Hosting extends Core\Models\Whmcs\Hosting
{

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'packageid');
    }

    public function productSettings()
    {
        return $this->hasOne(ProductSetting::class, 'product_id', 'packageid');
    }

    public function scopeActive($query)
    {
        return $query->where('domainstatus', 'Active');
    }

    public function scopeOfUserId($query, $userId)
    {
        return $query->where('userid', $userId);
    }

    public function scopeProductEnable($query)
    {
        $ps = (new ProductSetting)->getTable();
        $query->select("tblhosting.*");
        return $query->join($ps, function($join) use($ps){
                    $join->on('packageid', '=', "{$ps}.product_id");
                })->where( "{$ps}.enable",1);
    }
    
    public function scopeProductReseller($query)
    {
        $this->scopeActive($query);
        $ps = (new ProductSetting)->getTable();
        $p  = (new Product())->getTable();
        $query->select("tblhosting.*");
        return $query->rightJoin($ps, function($join) use($ps){
                    $join->on('packageid', '=', "{$ps}.product_id");
                })->rightJoin($p, function($join) use($p){
                    $join->on('packageid', '=', "{$p}.id");
                })->where( "{$ps}.enable",1)
                  ->whereIn("{$p}.type",["reselleraccount","server"]);
    }

    public function scopeOfStatus($query, $status)
    {
        return $query->whereIn('domainstatus', $status);
    }

    public function scopeOfUserAndServerTypePlesk($query,$userId)
    {
        $this->scopeOfStatus($query,['Active', 'Suspended']);
        $this->scopeOfUserId($query,$userId);
        $query->select("tblhosting.*");
        $p  = (new Product())->getTable();
        return $query->rightJoin($p, function($join) use($p){
                $join->on('packageid', '=', "{$p}.id");
            })->whereIn("{$p}.servertype",["plesk", "PleskExtended"]);
    }


}
