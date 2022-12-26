<?php

namespace ModulesGarden\Servers\AwsEc2\App\Models\AvailableImages;

use \ModulesGarden\Servers\AwsEc2\Core\Models\ExtendedEloquentModel;

/**
 * Description of AvailableImages
 *
 * @var int(10) id
 * @var varchar(128) image_id
 * @var int(10) product_id
 * @var text description
 * @var text details
 * @var varchar(128) name
 * @var varchar(64) region
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Model extends ExtendedEloquentModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'AvailableImages';

    protected $primaryKey = 'id';

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['product_id', 'details', 'image_id', 'description', 'name', 'region'];

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

    protected $casts = [
        'details' => 'array',
    ];
}
