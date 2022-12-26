<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\Models\Whmcs;

use \Illuminate\Database\Eloquent\model as EloquentModel;

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
        return $this->hasMany(ProductConfigOptions::class, 'gid', 'id');
    }

}
