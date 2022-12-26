<?php

namespace ModulesGarden\Servers\AwsEc2\App\Models\SSHKey;

use \ModulesGarden\Servers\AwsEc2\Core\Models\ExtendedEloquentModel;

/**
 * Description of AvailableImages
 *
 * @var int(10) id
 * @var varchar(255) public_key
 * @var varchar(255) private_key
 * @var varchar(255) salt
 *
 */
class SSHKeyModel extends ExtendedEloquentModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'SshKeys';

    protected $primaryKey = 'id';

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['service_id','public_key', 'private_key', 'salt'];

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

    protected $casts = [];
}
