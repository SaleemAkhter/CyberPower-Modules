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
use LKDev\HetznerCloud\Models\Firewalls\FirewallResource;
use LKDev\HetznerCloud\Models\Servers\Server;
use LKDev\HetznerCloud\Models\Servers\Servers;
use ModulesGarden\Servers\HetznerVps\App\Helpers\AccountActions;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\App\Service\CronTask\RegisterTask;
use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\AddonController;

/**
 * Terminate WHMCS Account and power off VM
 */
class TerminateAccount extends AddonController
{
    use AccountActions;

    public function execute($params = null)
    {
        $this->params = $params;
        $this->api = new Api($this->params);
        try {
            $this->checkServerIDIsNotEmpty();
            $this->removeKeyIfExist();
            $server = $this->api->servers()->get($this->api->getClient()->getServerID());
            //floating ips
            $toDelete = [];
            foreach ($this->api->floatingIps()->all() as $fIP)
                if ($fIP->server == $server->id)
                    array_push($toDelete, $fIP->id);
            if (count($toDelete) > 0)
                RegisterTask::deleteFloatingIP($toDelete, $this->params);
            //snapshots
            $this->api->snapshots()->findByServerId($server->id)->delete();
            $this->removeVolumes($server->volumes);
            //firewalls
            foreach ($this->api->firewalls()->all() as $firewall) {
                foreach ($firewall->appliedTo as $appiled) {
                    if ($appiled->server->id == $server->id) {
                        $firewall->removeFromResources(
                            [
                                new FirewallResource(FirewallResource::TYPE_SERVER,
                                    $server)
                            ]
                        );
                    }
                }
//                $firewall->delete();
            }
            $server->delete();
            $this->clearCronBackupsWithStatusOne();
            \ModulesGarden\Servers\HetznerVps\App\Service\CustomFields\CustomFields::set($this->params['serviceid'], 'serverID', '');
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
        return 'success';
    }
}