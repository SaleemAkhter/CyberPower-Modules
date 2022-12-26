<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs;

use \Illuminate\Database\Eloquent\model as EloquentModel;

/**
 * Description of CustomFieldValue
 *
 * @var fieldid
 * @var relid
 * @var value
 *
 * @author Paweł Złamaniec <pawel.zl@modulesgarden.com>
 */
class CustomFieldValue extends EloquentModel
{

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'tblcustomfieldsvalues';
    protected $primaryKey = 'id';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = [];

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['fieldid', 'relid', 'value'];

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

    /*
     * Relations to CustomField table
     * 
     */
    public function field()
    {
        return $this->hasOne(CustomField::class, 'id', 'fieldid');
    }

}
