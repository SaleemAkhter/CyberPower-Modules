<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Service\CronTask\Abstracts;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Helpers\Logger;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Projects\Resources;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Servers;

/**
 * Description of AbstractActions
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
abstract class AbstractActions
{

    protected function decodeParams($params)
    {
        return json_decode($params);
    }

    protected function getServerPassword($params)
    {
        $server = Servers::where('id', $params->serverID)->first();
        return [
            'serverpassword' => \decrypt($server->password)
        ];
    }
    
    protected function powerOnActions($params)
    {
        $params = $this->decodeParams($params);
        $api = new Api($this->getServerPassword($params));

        $action = $api->droplet->getDropletAction($params->dropletID, $params->actionID);
        if($action->action->status == 'in-progress')
        {
            throw new \Exception('Resize still in progress # '. $params->dropletID );
        }

        $api->droplet->powerOn($params->dropletID);
    }

    protected function deleteVolumeActions($params)
    {
        $params = $this->decodeParams($params);
        $api    = new Api($this->getServerPassword($params));
        $api->volume->deleteByID($params->id);
        echo "Success: Remove Volume #" . $params->id ."\n";
    }

    protected function assignVMActions($params)
    {
        $params = $this->decodeParams($params);


        $model =  new Resources();
        $model->setDroplet($params->dropletID);

        $api    = new Api($this->getServerPassword($params));
        $response = $api->projects->assign($model, $params->projectID);

        switch($response[0]->status)
        {
            case 'assigned':
                echo "Success: Assign droplet # ". $params->dropletID ."  to project " . $params->projectID ."\n";
                return;
             case 'already_assigned':
                echo "Success:  Droplet # ". $params->dropletID ." is already assign to project " . $params->projectID ."\n";
                return;
            case 'archived':
                echo "Info: Wrong droplet id # ". $params->dropletID ."\n";
                return;
        }

        throw new \Exception('Something goes wrong...');
    }
    protected function assignFloatingIpActions($params)
    {
        $params = $this->decodeParams($params);

        $api = new Api($this->getServerPassword($params));

        $response = $api->floatingIp->createAssigned($params->dropletID);

        echo 'Floating IP Created Successfully';

        return;
    }
    protected function assignFloatingIpToDropletIdActions($params)
    {
        $params = $this->decodeParams($params);
        $api = new Api($this->getServerPassword($params));

        if($params->createNew)
        {
            $api->floatingIp->createAssigned($params->dropletID);
        }
        else
        {
            $api->floatingIp->assign($params->ip,$params->dropletID);
        }

        echo 'Floating IP Assigned Successfully';

        return;
    }
    protected function unassignFloatingIpToDropletIdActions($params)
    {
        $params = $this->decodeParams($params);
        $api = new Api($this->getServerPassword($params));

        $api->floatingIp->unassign($params->ip);
        $api->floatingIp->removeFloatingIp($params->ip);

        echo 'Floating IP Unassigned Successfully';

        return;
    }
}
