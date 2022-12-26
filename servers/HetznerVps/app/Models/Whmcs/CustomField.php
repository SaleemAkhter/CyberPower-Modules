<?php

namespace ModulesGarden\Servers\HetznerVps\App\Models\Whmcs;

/**
 * Description of CustomField
 *
 * @property  int id
 * @property string type
 * @property string relid
 * @property string fieldname
 * @property string fieldtype
 * @property string description
 * @property string fieldoptions
 * @property string regexpr
 * @property string adminonly
 * @property string required
 * @property string showorder
 * @property string showinvoice
 * @property string sortorder
 * @property string created_at
 * @property string updated_at
 * @method  static $this ofClient()
 * @method  $this ofName()
 * @property CustomFieldValue $customFieldValue
 */
class CustomField extends \ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\CustomField
{


    public function scopeOfClient($query)
    {
        return $query->where('type', 'client');
    }

    public function scopeOfName($query, $name)
    {
        return $query->where('fieldname', 'LIKE', "{$name}|%");
    }

    public function customFieldValue()
    {
        return $this->hasMany(CustomFieldValue::class,"fieldid");
    }

}
