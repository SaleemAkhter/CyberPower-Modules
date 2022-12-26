<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Models;

use ModulesGarden\OvhVpsAndDedicated\Core\Models\ExtendedEloquentModel;

/**
 * Class MachineReuseProducts
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class MachineReuseProducts extends ExtendedEloquentModel
{

    protected $primaryKey = 'name';

    public $incrementing = false;
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'ReuseProducts';

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['name',  'productId'];

    /**
     * Indicates if the model should soft delete.
     *
     * @var bool
     */
    protected $softDelete = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}