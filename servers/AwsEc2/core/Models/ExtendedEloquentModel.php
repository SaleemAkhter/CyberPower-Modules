<?php

namespace ModulesGarden\Servers\AwsEc2\Core\Models;

use \Illuminate\Database\Eloquent\Model as EloquentModel;
use \ModulesGarden\Servers\AwsEc2\Core\ModuleConstants;

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
