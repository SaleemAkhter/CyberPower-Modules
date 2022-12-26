<?php

namespace ModulesGarden\WordpressManager\App\Models;

use \ModulesGarden\WordpressManager\Core\Models\ExtendedEloquentModel;

class ThemeBlocked extends ExtendedEloquentModel
{
    protected $table = 'ThemesBlocked';
    
    /**
     *
     *
     */
    protected $guarded = ['id'];

    /**
     *
     * @var array
     */
    protected $fillable   = ['name', 'slug', 'product_id'];
    protected $softDelete = false;
    public $timestamps    = false;
}
