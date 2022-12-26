<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Ovh;

use ModulesGarden\OvhVpsAndDedicated\Core\Models\ExtendedEloquentModel;

/**
 * Description of ServerSettings
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 *
 * @property string $server_id
 * @property string $setting
 * @property string $value
 */
class ServerSettings extends ExtendedEloquentModel
{

    protected $primaryKey = 'server_id';

    public $incrementing = false;

//    protected $visible = ['server_id', 'setting', 'value'];

    /**
     * Table name
     * 
     * @var string $table
     */
    protected $table = 'ServerSettings';

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['server_id', 'setting', 'value'];

    public $timestamps = false;
}
