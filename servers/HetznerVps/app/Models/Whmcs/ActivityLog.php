<?php


namespace ModulesGarden\Servers\HetznerVps\App\Models\Whmcs;

use Illuminate\Database\Eloquent\Model as EloquentModel;


/**
 * @property int $id
 * @property string $date
 * @property string $description
 * @property string $user
 * @property int $userid
 * @property string $ipaddr
 * @method static $this ofDescription($description)
 * @method  $this today()
 */
class ActivityLog extends EloquentModel
{
    /** @var string */
    protected $table = 'tblactivitylog';
    protected $fillable = ['id', 'date', 'description', 'user', 'userid', 'ipaddr'];


    public function scopeOfDescription($query, $description)
    {
        return $query->where('description', $description);
    }


    public function scopeToday($query)
    {
        return $query->whereRaw(' TIMESTAMP(`date`) >= TIMESTAMP(NOW()- INTERVAL 1 DAY )');
    }

}