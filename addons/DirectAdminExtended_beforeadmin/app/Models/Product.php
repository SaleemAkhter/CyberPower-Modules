<?php

namespace ModulesGarden\DirectAdminExtended\App\Models;

/**
* @property int $id
* @property string $setting
* @property int $product_id
* @property string $value
 *
 * @method whereProductAndSetting($productID, $settingName);
*/

class Product extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'DirectAdminExtended_Product';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = [];

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['setting', 'product_id', 'value'];

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


    public static function getSettings($productId)
    {
        $out      = [];
        $settings = self::where('product_id', $productId)->get();
        foreach($settings as $setting)
        {
            $out[$setting->setting] = $setting->value;
        }

        return $out;
    }

    public function scopeWhereProductAndSetting($query, $productId, $settingName)
    {
        return $query->where([
            ['product_id', $productId],
            ['setting', $settingName],
        ]);
    }

}
