<?php


namespace ModulesGarden\Servers\HetznerVps\App\Models\Whmcs;

use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * @property int $id
 * @property string $date
 * @property string $title
 * @property string $description
 * @property int $admin
 * @property string $status
 * @property string $duedate
 * @method static $this ofTitle($title)
 * @method this pending()
 */
class ToDoList extends EloquentModel
{
    protected $table = 'tbltodolist';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'date', 'title', 'description', 'admin', 'status', 'duedate'];
    public $timestamps = false;

    public function scopeOfTitle($query, $title)
    {
        return $query->where('title', $title);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }


}