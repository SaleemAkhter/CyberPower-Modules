<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Whmcs;

use ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\CustomField as CoreCustomField;

/**
 * Class CustomFieldValue
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class CustomField extends CoreCustomField
{
    public function values()
    {
        return $this->hasMany(CustomFieldValue::class, 'fieldid', 'id');
    }

}