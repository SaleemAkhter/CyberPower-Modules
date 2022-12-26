<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Models;

use ModulesGarden\OvhVpsAndDedicated\Core\Models\ExtendedEloquentModel;

/**
 * Description of Vps
 * 
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Machine extends ExtendedEloquentModel
{

    protected $primaryKey = 'name';

    public $incrementing = false;
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'Machine';

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['name',  'setting', 'value'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
