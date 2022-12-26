<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Providers\Service;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\Hosting;
use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\sl;


/**
 * Description of CustomFields
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Provider
{
    const OVH_SERVER = 'Ovh';

    protected $params;

    public function __construct($params = [])
    {
        if(isset($params['serverid']))
        {
            $this->params = $params + $this->loadByServerId((int)$params['serverid']);
            return;
        }

        if (empty($params) || !$params)
        {
            $params = $this->getWHMCSParams();
        }
        $this->params = $params;
    }

    private function loadByServerId($serverId)
    {
        $server = new \WHMCS\Module\Server();
        return $server->getServerParams($serverId);;
    }


    private function getWHMCSParams()
    {
        $server = new \WHMCS\Module\Server();

        $server->loadByServiceID($this->getHostingID());
        return $server->buildParams();
    }


    private function getHostingID()
    {
        $request = sl('request');

        if (!empty($request->get('productselect')))
        {
            return $request->get('productselect');
        }
        else if (!empty($request->get('id')))
        {
            return $request->get('id');
        }

        return $this->getIDFromDB();
    }

    private function getIDFromDB()
    {
        $request = sl('request');

        $hosting = Hosting::where(['userid' => $request->get('userid')])->orderBy('domain', 'ASC')->first();
        return $hosting->id;
    }

    function getParams()
    {
        return $this->params;
    }

}
