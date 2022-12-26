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
 * @method public WhereHostingID(integer $hostingID)
 * 
 * @property int $id
 * @property int $hosting_id
 * @property string $task
 */
class TaskHistory extends EloquentModel
{
    /*
     * Table name
     * 
     * @var string $table
     */

    protected $table = 'MG_DigitalOceanDroplets_task_history';

    
        /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['hosting_id', 'task'];
            
    /*
     * Scope to get task where hosting ID
     * 
     * @param integer $serviceID
     */

    public function scopeWhereHostingID($query, $hostingID)
    {
        return $query->where('hosting_id', $hostingID);
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
            $table->integer('hosting_id');
            $table->string('task', 255);
            $table->timestamps();
            $table->foreign('hosting_id')->references('id')->on('tblhosting');
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
