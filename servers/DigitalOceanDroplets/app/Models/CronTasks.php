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
 * @method public whereMailID(integer $mailID)
 * 
 * @property int $id
 * @property int $hosting_id
 * @property int $mail_id
 * @property string $status
 * @property string $message
 */
class CronTasks extends EloquentModel
{
    /*
     * Table name
     * 
     * @var string $table
     */

    protected $table = 'MG_DigitalOceanDroplets_cron_task';

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['action', 'params', 'status', 'message'];

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
            $table->string('action');
            $table->text('params');
            $table->string('status')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
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
