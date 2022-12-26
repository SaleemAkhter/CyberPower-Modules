<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Models;

use \Illuminate\Database\Eloquent\model as EloquentModel;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleConstants;

/**
 * Wrapper for EloquentModel
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ExtendedEloquentModel extends EloquentModel
{
    public function __construct(array $attributes = [])
    {
        $this->table = ModuleConstants::getPrefixDataBase() . $this->table;
        
        parent::__construct($attributes);
    }
}
