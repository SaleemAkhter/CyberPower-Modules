<?php
/**********************************************************************
 * HetznerVps developed. (26.03.19)
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
 **********************************************************************/

namespace ModulesGarden\Servers\HetznerVps\App\Http\Actions;

use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\AddonController;

class MetaData extends AddonController
{
    public function execute($params = null)
    {
        return [
            'DisplayName'    => 'Hetzner VPS',
            'RequiresServer' => true,
            'DefaultNonSSLPort' => '8006', // Default Non-SSL Connection Port
            'DefaultSSLPort' => '8006', // Default SSL Connection Port
        ];

    }

}