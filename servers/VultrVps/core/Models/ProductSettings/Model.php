<?php

namespace ModulesGarden\Servers\VultrVps\Core\Models\ProductSettings;

use ModulesGarden\Servers\VultrVps\Core\Models\ExtendedEloquentModel;

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

    protected $primaryKey = ['product_id', 'setting'];
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
    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery( $query)
    {
        $keys = $this->getKeyName();
        if(!is_array($keys)){
            return parent::setKeysForSaveQuery($query);
        }

        foreach($keys as $keyName){
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }
        return $query;
    }

    /**
     * Get the primary key value for a save query.
     *
     * @param mixed $keyName
     * @return mixed
     */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if(is_null($keyName)){
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

}

