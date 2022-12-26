<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Models;

use \Illuminate\Database\Eloquent\Model as EloquentModel;

class AddonModule extends EloquentModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'tbladdonmodules';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = [];

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['module', 'setting', 'value'];

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
