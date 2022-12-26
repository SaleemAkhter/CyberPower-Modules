<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Whmcs;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\ProductConfigOption;
use \Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Description of ProductConfigGroups
 *
 * @author Mateusz Pawłowski <mateusz.pa@moduelsgarden.com>
 *
 * @property int $id
 * @property string $name
 * @property string $description
 */
class ProductConfigGroups extends EloquentModel
{

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'tblproductconfiggroups';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function options()
    {
        return $this->hasMany(ProductConfigOption::class, 'gid', 'id');
    }

}
