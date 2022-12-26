<?php

namespace ModulesGarden\Servers\HetznerVps\App\Models;

use Illuminate\Database\Eloquent\model as EloquentModel;
use Illuminate\Database\Schema\Blueprint;
use WHMCS\Database\Capsule;

/**
 * Description of TaskHistory
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 *
 *
 * @method public whereHostingID(integer $hostingID)
 *
 * @property int $id
 * @property int $product_id
 * @property string $task
 * @method static ProductConfiguration ofProductId($productId)
 * @method static ProductConfiguration ofSettings($productId)
 */
class ProductConfiguration extends EloquentModel
{
    /*
     * Table name
     * 
     * @var string $table
     */

    protected $table = 'MG_HetznerVps_product_configuration';

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['product_id', 'setting', 'value'];
    public $timestamps = false;

    /*
     * Scope to get task where hosting ID
     * 
     * @param integer $serviceID
     */

    public function scopeWhereHostingID($query, $hostingID)
    {
        return $query->where('product_id', $hostingID);
    }

    //////////////////////////// Create Table //////////////////////////////////////

    /*
     * Check table exist
     * 
     * @return boolean
     */
    public function tableExists()
    {
        return Capsule::Schema()->hasTable($this->table);
    }

    /*
     * Extend column length
     */
    public function extentColumnLengthIfTooSmall()
    {
        $column = Capsule::table('information_schema.columns')->select('*')->where(['table_name' => $this->table, 'COLUMN_NAME' => 'value'])->first();

        if (!$column || $column->CHARACTER_MAXIMUM_LENGTH >= 500) {
            return;
        }

        Capsule::statement("ALTER TABLE MG_HetznerVps_product_configuration MODIFY value varchar(500)");
    }

    /*
     * Create table
     * 
     * @return void
     */

    protected function createTable()
    {
        Capsule::schema()->create($this->table, function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id')->unique();
            $table->integer('product_id');
            $table->string('setting', 255);
            $table->string('value', 500);
        });
    }

    /*
     * Check and create if not exist
     * 
     * @return void
     */

    public function createOrUpdateTable()
    {
        !$this->tableExists() ? $this->createTable() : $this->extentColumnLengthIfTooSmall();
    }

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
