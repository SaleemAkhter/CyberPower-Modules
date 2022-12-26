<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\Hosting;
use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\sl;

/**
 * Description of ServiceManager
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ServiceManager
{
    protected $params;

    public function __construct($params = [])
    {
        if (empty($params))
        {
            $params = $this->getWHMCSParams();
        }
        $this->params = $params;
    }

    public function getWHMCSParams()
    {
        return self::getWhmcsParamsByHostingId($this->getHostingID());
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
        else
        {
            return $this->getIDFromDB();
        }
    }

    private function getIDFromDB()
    {
        $request = sl('request');
        $hosting = Hosting::where([
            'userid' => $request->get('userid')
        ])->orderBy('domain', 'ASC')->first();
        return $hosting->id;
    }

    public function getParams()
    {
        return $this->params;
    }

    public static function getWhmcsParamsByHostingId($hostingId)
    {
        $server = new \WHMCS\Module\Server();
        $server->loadByServiceID($hostingId);
        return $server->buildParams();
    }
}
