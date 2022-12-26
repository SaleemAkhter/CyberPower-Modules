<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Product\Helpers;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Hosting;

/**
 * Description of ServerDetails
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
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
        if (!empty($_REQUEST['productselect']))
        {
            self::$hostingID = $_REQUEST['productselect'];
        }
        elseif (!empty($_REQUEST['id']))
        {
            self::$hostingID = $_REQUEST['id'];
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
        $hosting = Hosting::where([
                    'userid' => $_REQUEST['userid']
                ])->orderBy('domain', 'ASC')->first();

        if (is_null($hosting))
        {
            throw new Exception();
        }
        self::$hostingID = $hosting->id;
    }

}
