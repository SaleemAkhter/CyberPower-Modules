<?php

namespace ModulesGarden\DirectAdminExtended\App\Models;

/**
 * @property int $id
 * @property int $product_id
 * @property int $server_id
 * @property string $name
 * @property string $host
 * @property int $port
 * @property string $user
 * @property string $password
 * @property string $path
 * @property string $admin_access
 * @property string $enable_restore
 */

class FTPEndPoints extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'DirectAdminExtended_FTPEndPoints';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['product_id', 'server_id', 'name', 'user', 'host','port','password','path','admin_access','enable_restore'];

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

    public static function factory($id = null)
    {
        if ($id !== null && self::where('id', '=', $id)->first())
        {
            return self::where('id', '=', $id)->first();
        }

        return new self();
    }

}
