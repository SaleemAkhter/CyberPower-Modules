<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Ovh;

use ModulesGarden\OvhVpsAndDedicated\Core\Models\ExtendedEloquentModel;


/**
 * Description of Orders
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 *
 *
 * @property int $id
 * @property int $hosting_id
 * @property int $order_id
 */
class Orders extends ExtendedEloquentModel
{
    /*
     * Table name
     * 
     * @var string $table
     */

    protected $table = 'Orders';

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['hosting_id', 'order_id'];

    public $timestamps = false;

}
