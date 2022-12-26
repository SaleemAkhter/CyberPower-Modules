<?php

namespace ModulesGarden\WordpressManager\App\Models;

use ModulesGarden\WordpressManager\Core\Models\ExtendedEloquentModel;

class WebsiteDetails extends  ExtendedEloquentModel
{
    protected $primaryKey = 'wpid';

    protected $table      = 'WebsiteDetails';
    protected $fillable   = ['wpid', 'desktop', 'mobile', 'screenshot', 'created_at', 'updated_at'];
    protected $casts      = [
        'mobile'     => 'array',
        'desktop'    => 'array',
        'screenshot' => 'string',
    ];
    public $timestamps    = true;

}
