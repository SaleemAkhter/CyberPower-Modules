<?php

namespace ModulesGarden\DirectAdminExtended\App\Models;

use \Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{

    public static function factory($id = null)
    {
        if ($id != null)
        {
            return new self(array('id' => $id));
        }
        else
        {
            return new self();
        }
    }
}
