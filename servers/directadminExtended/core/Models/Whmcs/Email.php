<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\Models\Whmcs;

use \Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Description of Product
 *
 * @author Paweł Złamaniec <pawel.zl@modulesgarden.com>
 */
class Email extends EloquentModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'tblemails';

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
    protected $fillable = ["userid", "subject", "message", "date", "to", "cc", "bcc"];

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
    
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Add relation to client
     *
     * @return type
     */
    public function client()
    {
        return $this->belongsTo("ModulesGarden\Servers\DirectAdminExtended\Core\Models\Whmcs\Client", "userid");
    }
}
