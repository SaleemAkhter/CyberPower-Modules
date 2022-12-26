<?php

/* * ********************************************************************
 * Servers\HetznerVps product developed. (Jan 17, 2019)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\Servers\HetznerVps\App\Models\Whmcs;


/**
 * Description of Hosting
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 * @property int $fieldid
 * @property int $relid
 * @property int $value
 * @property CustomField $customField
 * @method $this ofRelid($id)
 */
class CustomFieldValue extends \ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\CustomFieldValue
{


    public function customField()
    {
        return $this->hasOne("ModulesGarden\Servers\HetznerVps\App\Models\Whmcs\CustomField", "id", "fieldid");
    }

    public function scopeOfRelid($query, $id){
        return $query->where('relid', $id);
    }

}
