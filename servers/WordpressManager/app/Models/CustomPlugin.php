<?php

namespace ModulesGarden\WordpressManager\App\Models;

use \ModulesGarden\WordpressManager\Core\Models\ExtendedEloquentModel;

class CustomPlugin extends ExtendedEloquentModel
{
    protected $table = 'CustomPlugin';
    
    protected $guarded = ['id'];

    protected $fillable   = ['name', 'description', 'url', 'version', 'enable'];
    protected $softDelete = false;
    public $timestamps    = false;

    public function scopeOfId($query, array $ids)
    {
        return $query->whereIn('id', $ids);
    }

    public function scopeEnable($query)
    {
        return $query->where('enable', 1);
    }

}
