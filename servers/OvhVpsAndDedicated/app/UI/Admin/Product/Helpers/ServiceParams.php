<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Helpers;

use Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\Hosting;
use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\sl;

/**
 * Description of ServerDetails
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ServiceParams
{
    /*
     * @var integer $hostingID;
     */

    protected static $hostingID;

    /*
     * Check is service is a Digital Ocean product
     * 
     * @return object \ModulesGarden\Servers\DigitalOcean\Core\Models\Whmcs\Hosting
     * @throw \Exception
     */

    public static function checkAndGet()
    {
        self::setHostingID();

        $hosting = Hosting::where([
                    'id' => self::$hostingID,
                ])->with('product')->whereHas('product', function($q)
                {
                    $q->where('servertype', 'DigitalOcean');
                })->first();
        if (is_null($hosting))
        {
            throw new Exception(Lang::getInstance()->T('wrongServiceType'));
        }
        return $hosting;
    }

    /*
     * 
     */

    public static function getHostings()
    {
        self::setHostingID();
        try
        {
            return Hosting::where([
                        'id' => self::$hostingID,
                    ])->with(['product', 'servers'])->first();
        }
        catch (Exception $ex)
        {

        }
    }

    /*
     * 
     */

    public static function getParams()
    {
        self::setHostingID();
        $server = new \WHMCS\Module\Server();
        $server->loadByServiceID(self::$hostingID);
        return $server->buildParams();
    }

    /*
     * Set WHMCS service ID
     * 
     * 
     * @return void
     */

    private static function setHostingID()
    {
        $request = sl('request');

        if (!empty($request->get('productselect')))
        {
            self::$hostingID = $request->get('productselect');
        }
        elseif (!empty($request->get('id')))
        {
            self::$hostingID = $request->get('id');
        }
        else
        {
            self::getIDFromDB();
        }
    }

    /*
     * Get default whmcs service ID
     * 
     * @throw \Exception
     * 
     * @return void
     */

    private static function getIDFromDB()
    {
        $request = sl('request');
        $hosting = Hosting::where([
                    'userid' => $request->get('userid')
                ])->orderBy('domain', 'ASC')->first();

        if (is_null($hosting))
        {
            throw new Exception();
        }
        self::$hostingID = $hosting->id;
    }

}
