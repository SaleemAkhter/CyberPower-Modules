<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Whmcs;

use ModulesGarden\OvhVpsAndDedicated\Core\Models\ExtendedEloquentModel;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\Product;
use \Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Description of ProductConfigLinks
 *
 * @author Mateusz Pawłowski <mateusz.pa@moduelsgarden.com>
 *
 * @property int $gid
 * @property int $pid
 */
class ProductConfigLinks extends EloquentModel
{

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'tblproductconfiglinks';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function groups()
    {
        return $this->hasOne(ProductConfigGroups::class, 'id', 'gid');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'pid');
    }

}
