<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Helpers;

use Exception;
use LKDev\HetznerCloud\RequestOpts;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Models\Images\Criteria;
use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;
use ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\Product;
use ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\ServersRelations;

/**
 * Description of Config
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Config
{

    protected static $serverPassword;

    public static function getProductValues()
    {
        return Product::where('id', $_REQUEST['id'])->first();
    }

    private static function setServerDetails()
    {
        if (!empty($_REQUEST['servergroup']))
        {
            $serverGroupID = $_REQUEST['servergroup'];
        }
        else
        {
            $serverGroupID = self::getProductValues()->servergroup;
        }


        $server                                 = ServersRelations::where('groupid', $serverGroupID)->with('servers')->first();
        self::$serverPassword['serverpassword'] = \decrypt($server->servers->password);
    }

    /**
     * @return array
     */
    public static function getLocations()
    {
        self::setServerDetails();

        $api     = new Api(self::$serverPassword);
        $locations = $api->locations()->all();

        return self::prepareList($locations, 'id', 'city');
    }

    /**
     * @return array
     */
    public static function getDatacenter()
    {
        self::setServerDetails();

        $api     = new Api(self::$serverPassword);
        $locations = $api->datacenters()->all();


        return self::prepareListWithEmptyOption($locations, 'id', 'description');
    }

    /**
     * @return array
     */
    public static function getImages()
    {
        self::setServerDetails();

        $api     = new Api(self::$serverPassword);
        $imgageList = $api->images()->all();
        foreach($imgageList as $k => $entity){
            if(!in_array($entity->type, ['system'])){

            }
        }

        return self::prepareList($imgageList, 'id', 'description');
    }

    public static function getImagesWithoutBackups()
    {
        self::setServerDetails();

        $api     = new Api(self::$serverPassword);
        $images = $api->images()->all();
//        foreach ($images as &$image) {
//            if($image->type == 'backup')
//                unset($image);
//        }
        $images = array_filter($images, function($image) {
            if($image->type =='backup')
                return false;
            return true;
        });

        return self::prepareList($images, 'id', 'description');
    }

    /**
     * @return array
     */
    public static function getFloatingIps()
    {
        self::setServerDetails();

        $api     = new Api(self::$serverPassword);
        $floatingIpsList = $api->images()->all();
        foreach($floatingIpsList as $k => $entity){
            if(!in_array($entity->type, ['system'])){

            }
        }

        return self::prepareList($floatingIpsList, 'id', 'description');
    }

    public static function getIsos()
    {
        self::setServerDetails();

        $api     = new Api(self::$serverPassword);
        $imgageList = $api->isos()->all(new RequestOpts(50));

        return self::prepareList($imgageList, 'id', 'description');
    }
    /**
     * @return array
     */
    public static function getTypes()
    {
        self::setServerDetails();

        $api     = new Api(self::$serverPassword);
        $typesList = $api->serverTypes()->all();

        return self::prepareList($typesList, 'id', 'description');
    }

    /**
     * @param array $list
     * @param $id
     * @param $value
     * @return array
     */
    protected static function prepareList($list = [], $id, $value)
    {
        $itemList = [];

        foreach($list as $location)
        {
            $itemList[$location->{$id}] = $location->{$value};
        }

        return $itemList;
    }

    protected static function prepareListWithEmptyOption($list = [], $id, $value)
    {
        $itemList[0] = Lang::getInstance()->absoluteT('auto');

        foreach($list as $location)
        {
            $itemList[$location->{$id}] = $location->{$value};
        }

        return $itemList;
    }

}
