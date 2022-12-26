<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs;

use \Illuminate\Database\Eloquent\model as EloquentModel;

/**
 * Description of ProductConfigOptionsSub
 *
 * @author Mateusz Pawłowski <mateusz.pa@moduelsgarden.com>
 * 
 * @property int $id
 * @property int $configid
 * @property string $optionname
 * @property int $sortorder
 * @property int $hidden
 */
class ProductConfigOptionsSub extends EloquentModel
{

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'tblproductconfigoptionssub';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['configid', 'optionname', 'sortorder', 'hidden'];

}
