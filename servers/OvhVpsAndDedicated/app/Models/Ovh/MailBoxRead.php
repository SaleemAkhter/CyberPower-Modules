<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Ovh;

use ModulesGarden\OvhVpsAndDedicated\Core\Models\ExtendedEloquentModel;


/**
 * Description of TaskHistory
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
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
class MailBoxRead extends ExtendedEloquentModel
{
    /*
     * Table name
     * 
     * @var string $table
     */

    protected $table = 'MailboxRead';

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['hosting_id', 'product_id', 'mail_id', 'status', 'message', 'mail'];

    public $timestamps = false;
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

    public function scopeWhereProductId($query, $productId)
    {
        return $query->where('product_id', $productId);
    }
}
