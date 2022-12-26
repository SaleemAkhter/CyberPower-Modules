<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Models;

use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ExtendedEloquentModel;

class FunctionsSettings extends ExtendedEloquentModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'FunctionsSettings';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = [];

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['product_id', 'ftp', 'emails'];

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

    public function product()
    {
        return $this->belongsTo("ModulesGarden\Servers\DirectAdminExtended\Core\Models\Whmcs\Product", "product_id");
    }

    public static function factory($id = null)
    {
        if ($id !== null && self::where('product_id', '=', $id)->first())
        {
            return self::where('product_id', '=', $id)->first();
        }

        return new self();
    }

    public function hasAutoinstaller()
    {
        if ($this->autoinstaller)
        {
            return true;
        }

        return false;
    }

    public function hasAutoCreate()
    {
        return $this->autoinstall_on_create === 'on';
    }

    public function useCO()
    {
        return $this->order_assign == 1;
    }

    public function getAppName()
    {
        return $this->app_name;
    }
}
