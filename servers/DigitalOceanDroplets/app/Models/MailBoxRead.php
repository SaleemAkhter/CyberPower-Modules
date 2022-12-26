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
 * @method whereHostingID(integer $hostingID)
 * @method whereMailID(integer $mailID)
 * @method whereMail(string $mail)
 *
 * @property int $id
 * @property int $hosting_id
 * @property int $mail_id
 * @property string $status
 * @property string $message
 * @property string $mail
 */
class MailBoxRead extends EloquentModel
{
    /*
     * Table name
     * 
     * @var string $table
     */

    protected $table = 'MG_DigitalOceanDroplets_mailbox_read';

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['hosting_id', 'mail_id', 'status', 'message', 'mail'];

    /*
     * Scope to get mail where hosting ID
     * 
     * @param integer $serviceID
     */

    public function scopeWhereHostingID($query, $hostingID)
    {
        return $query->where('hosting_id', $hostingID);
    }

    /*
     * Scope to get mail where mail ID
     * 
     * @param integer $serviceID
     */

    public function scopeWhereMailID($query, $mailID)
    {
        return $query->where('mail_id', $mailID);
    }
    public function scopeWhereMail($query, $mail)
    {
        return $query->where('mail', $mail);
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
            $table->integer('mail_id');
            $table->string('mail');
            $table->string('status');
            $table->text('message');
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

    public function checkColumnExist($columnName){
        return Capsule::Schema()->hasColumn($this->table, $columnName);
    }

    protected function addColumn($columnName, $columnType){
        Capsule::Schema()->table($this->table, function($table) use($columnName, $columnType)
        {
            $table->{$columnType}($columnName);
        });
    }
    public function addColumnIfNotExists($columnName, $columnType){

        if(!$this->checkColumnExist($columnName)){
            $this->addColumn($columnName, $columnType);
        }
    }

    public function checkAndAddMailColumn(){
        if (!Capsule::Schema()->hasColumn($this->table, 'mail'))
        {


            return true;
        }

    }

}
