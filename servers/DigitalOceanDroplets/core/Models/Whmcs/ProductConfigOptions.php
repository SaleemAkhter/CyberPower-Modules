<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs;

use \Illuminate\Database\Eloquent\model as EloquentModel;

/**
 * Description of ProductConfigOptions
 *
 * @author Mateusz Pawłowski <mateusz.pa@moduelsgarden.com>
 * @property int $id
 * @property int $gid
 * @property string $optionname
 * @property string $optiontype
 * @property int $qtyminimum
 * @property int $qtymaximum
 * @property int $order
 * @property int $hidden
 */
class ProductConfigOptions extends EloquentModel
{

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'tblproductconfigoptions';

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['gid', 'optionname', 'optiontype', 'qtyminimum', 'qtymaximum', 'order', 'hidden'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function groups()
    {
        $this->hasOne(ProductConfigGroups::class, 'id', 'gid');
    }

}
