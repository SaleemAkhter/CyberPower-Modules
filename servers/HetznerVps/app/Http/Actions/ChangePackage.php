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

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Helpers\AccountActions;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;

/**
 * Resize droplet
 */
class ChangePackage extends AddonController
{
    use AccountActions;

    public function execute($params = null)
    {
        $this->params = $params;
        $this->api = new Api($this->params);
        try {
            $this->clearCronDB();
            $this->checkServerIDIsNotEmpty();
            $server = $this->api->servers()->get($this->api->getClient()->getServerID());
            //Floating IPs
            $this->distributeFloatingIps();
            //Backups
            $this->backupsPermission($server->id);
            //Change Type
            $newType = $this->getType();
            if ($newType->id !== $server->serverType->id) {

                if (strtolower($server->status) == "off") {
                    try {
                        $server->changeType($newType, true);
                    } catch (Exception $ex) {
                        throw new Exception(Lang::getInstance()->absoluteT('downgradeError'));
                    }
                } else {
                    throw new Exception(Lang::getInstance()->absoluteT('serverMustOff'));
                }
            } else {
                //Change Image;
                $newImage = $this->getImage();
                if ($newImage->id !== $server->image->id) {
                    $server->rebuildFromImage($newImage);
                }
            }
            //Resize Volumes
            $this->resizeVolumes($server->volumes);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
        return 'success';
    }
}