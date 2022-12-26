<?php

namespace ModulesGarden\Servers\HetznerVps\Core\Models;

use \Illuminate\Database\Eloquent\Model as EloquentModel;
use \ModulesGarden\Servers\HetznerVps\Core\ModuleConstants;

/**
 * Wrapper for EloquentModel
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ExtendedEloquentModel extends EloquentModel
{
    public function __construct(array $attributes = [])
    {
        $this->table = \ModulesGarden\Servers\HetznerVps\Core\ModuleConstants::getPrefixDataBase() . $this->table;
        
        parent::__construct($attributes);
    }
}
