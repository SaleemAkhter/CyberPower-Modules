<?php

namespace ModulesGarden\Servers\HetznerVps\App\Service\CronTask\Abstracts;

use Cassandra\Exception\RangeException;
use http\Exception\BadConversionException;
use http\Exception\BadMessageException;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Models\Projects\Resources;
use ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\Servers;
use LKDev\HetznerCloud\Models\Servers\Server;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

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
        $params->serverID;
        return [
            'serverpassword' => \decrypt($server->password)
        ];
    }

    protected function deleteVolumeActions($params)
    {
        $params = $this->decodeParams($params);
        $api    = new Api($this->getServerPassword($params));

        $volumeObject = $api->volumes()->get($params->id);
        $volumeObject->delete();

        echo "Success: Volume #" . $params->id ." has been unattached successfully\n";
    }

    protected function attachVolumeActions($params)
    {
        $params = $this->decodeParams($params);
        $api    = new Api($this->getServerPassword($params));

        $maxTime = time()+10;
        do
        {
            $server = $api->servers()->get($params->serviceID);
            sleep(1);
        }
        while (empty($server->volumes) && $maxTime >= time());

        if (!empty($server->volumes) || empty($server))
        {
            return;
        }

        $volumeObject = $api->volumes()->get($params->id);
        $volumeObject->attach(new Server($params->serviceID));

        echo "Success: Volume #" . $params->id ." has been attached successfully\n";
    }

    protected function deleteFloatingIPActions($params)
    {
        $params = $this->decodeParamsAsArray($params);
        $ipsToDelete = $params['ipsToDelete'];
        $api = new Api($params);
        $floatingIPs = $api->floatingIps();
        $toshow = null;
        foreach ($ipsToDelete as $id){
            $toshow = $floatingIPs->delete($id);
            echo "Success: Floating IP #" . $id ." has been deleted successfully\n";
        }
    }

    protected function createFloatingIPActions($params)
    {
        $params = $this->decodeParamsAsArray($params);
        $data = $params['createData'];
        $api = new Api($params);
        $floatingIPs = $api->floatingIps();
        $server = $api->servers()->get($data['serverID']);
        if(!$server)
            return;

        $resp = $floatingIPs->create(
            $data['type'],
            '',
            null,
            $server
        );
        echo "Success: Floating IP #" . $resp->id ." has been created successfully\n";
    }

    protected function enableBackupsActions($params)
    {
        $params = $this->decodeParamsAsArray($params);
        $api = new Api($params);
        $server = $api->servers()->get($params['serverID']);
        if(!$server)
            return;
        $api->servers()->get($params['serverID'])->enableBackups();
        echo "Success: Backups have been enabled\n";

    }
    protected function disableBackupsActions($params)
    {
        $params = $this->decodeParamsAsArray($params);
        $api = new Api($params);
        $server = $api->servers()->get($params['serverID']);
        if(!$server)
            return;
        $api->servers()->get($params['serverID'])->disableBackups();
        echo "Success: Backups have been disabled\n";
    }

    protected function decodeParamsAsArray($params)
    {
        return json_decode($params, true);
    }
}
