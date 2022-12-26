<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Whmcs;

use ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\CustomFieldValue as CoreCustomFieldValue;

/**
 * Class CustomFieldValue
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class CustomFieldValue extends CoreCustomFieldValue
{
    public function field()
    {
        return $this->hasOne(CustomField::class, 'id', 'fieldid');
    }

}