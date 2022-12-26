<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Models\ProductSettings;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Models\ExtendedEloquentModel;

/**
 * @property string $setting
 * @property int $product_id
 * @property string $value
 * @method static Model ofProductId($productId)
 * @method static Model ofSetting($productId)
 */
class Model extends ExtendedEloquentModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'ProductSettings';

    protected $primaryKey = 'setting';
    public $incrementing = false;
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'array',
    ];

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['product_id', 'setting', 'value'];

    public $timestamps = false;

    public function scopeOfProductId($query, $productId)
    {
        return $query->where("product_id", $productId);
    }

    public function scopeOfSetting($query, $setting)
    {
        return $query->where("setting", $setting);
    }

    public function scopeOfValue($query, $value)
    {
        return $query->where("value", $value);
    }
}

