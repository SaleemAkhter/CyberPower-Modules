<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Ovh;

use ModulesGarden\OvhVpsAndDedicated\Core\Models\ExtendedEloquentModel;

/**
 * Description of TaskHistory
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 * 
 * 
 * @method public whereHostingID(integer $hostingID)
 * 
 * @property int $id
 * @property int $product_id
 * @property string $task
 */
class ProductConfiguration extends ExtendedEloquentModel
{
    /*
     * Table name
     * 
     * @var string $table
     */

    protected $table = 'ProductConfiguration';

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['product_id', 'setting', 'value'];

    public $timestamps = false;

    /**
     * Scope to get task where hosting ID
     * 
     * @param integer $serviceID
     *
     * @return $query
     */
    public function scopeWhereHostingID($query, $hostingID)
    {
        return $query->where('product_id', $hostingID);
    }
}
