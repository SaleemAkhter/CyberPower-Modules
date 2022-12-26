<?php

namespace ModulesGarden\WordpressManager\App\Models;

use \ModulesGarden\WordpressManager\Core\Models\ExtendedEloquentModel;

class CustomTheme extends ExtendedEloquentModel
{
    protected $table = 'CustomTheme';
    
    protected $guarded = ['id'];

    protected $fillable   = ['name', 'description', 'url', 'version', 'enable'];
    protected $softDelete = false;
    public $timestamps    = false;
   
}
