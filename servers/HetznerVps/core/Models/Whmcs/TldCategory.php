<?php

namespace ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs;

use \Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Description of TldCategory
 *
 * @author Paweł Złamaniec <pawel.zl@modulesgarden.com>
 */
class TldCategory extends EloquentModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'tbltld_categories';

    protected $primaryKey = 'id';
    
    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['category', 'is_primary', 'display_order'];

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
    public $timestamps = true;
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
