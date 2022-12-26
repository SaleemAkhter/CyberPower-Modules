<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Models;

use \Illuminate\Database\Eloquent\model as EloquentModel;
use \Illuminate\Database\Schema\Blueprint;
use \WHMCS\Database\Capsule;

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
 */
class ProductConfiguration extends EloquentModel
{
    /*
     * Table name
     * 
     * @var string $table
     */

    protected $table = 'MG_DigitalOceanDroplets_product_configuration';

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
     * Create table
     * 
     * @return void
     */

    protected function createTable()
    {
        Capsule::schema()->create($this->table, function (Blueprint $table)
        {
            $table->charset   = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id')->unique();
            $table->integer('product_id');
            $table->string('setting', 255);
            $table->string('value', 255);
//            $table->foreign('product_id')->references('id')->on('tblproducts');
        });
    }

    /*
     * Check and create if not exist
     * 
     * @return void
     */

    public function createTableIfNotExists()
    {
        if (!$this->tableExists())
        {
            $this->createTable();
        }
    }

}
