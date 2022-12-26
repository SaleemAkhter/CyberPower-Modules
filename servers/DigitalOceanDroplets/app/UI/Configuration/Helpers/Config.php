<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Helpers;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Helpers\SlugNameBuilder;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Images\Criteria;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Product;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\ServersRelations;
use DigitalOceanV2\Entity\Size;

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
    
    

    public static function  getRegionsAndSiezes()
    {

        self::setServerDetails();
        $api     = new Api(self::$serverPassword);
        $regions = $api->region->all();
        return [
            'size'    => self::prepareSizeTable($regions[0]->sizes),
            'regions' => self::prepareRegionsTable($regions)
        ];
    }

    public static function getProjectList()
    {

        self::setServerDetails();
        $api     = new Api(self::$serverPassword);
        $projectList = $api->projects->all();

        return self::prepareProjectsTable($projectList);
    }

    public static function getImagesList($criteria =  null)
    {

        self::setServerDetails();
        $api      = new Api(self::$serverPassword);
        $images = $api->image->all($criteria);



        return self::prepareImagesTable($images, $criteria);
    }

    public static function getSslType()
    {
        return [
            'NONE' => 'NONE',
            'SSL'  => 'SSL',
            'TLS'  => 'TLS'
        ];
    }
    
    public static function getSshKeys(){
        self::setServerDetails();
        $api      = new Api(self::$serverPassword);
        $keys = $api->key->all();
        

        return self::prepareKeysArray($keys);
    }
    
    private function prepareKeysArray(array $keys){
        
        $keysArray = array();
        foreach ($keys as $key){
            $keysArray[$key->getItemObject()->id] = $key->getItemObject()->name;
        }
        asort($keysArray);
        $k = array(0 => '-None-') + $keysArray;
        return array_unique($k);
    }

    private function prepareImagesTable($images, $criteria = null)
    {

        $imagesArray = [];
        foreach ($images as $image)
        {
            if(!is_null($criteria) && $image->public === false){
                continue;
            }
            $imagesArray[$image->id] = $image->distribution . ' ' . $image->name;
        }
        return $imagesArray;
    }

    private function prepareRegionsTable($regions)
    {
        $regionsArray = [];

        foreach ($regions as $region)
        {
            $regionsArray[$region->slug] = $region->name;
        }
        asort($regionsArray);
        return $regionsArray;
    }

    private function prepareSizeTable($sizes)
    {
        $sizesArray = [];
        $api      = new Api(self::$serverPassword);
        $slugs = $api->slug->all();
        $slugBuilder = new SlugNameBuilder();
       
        foreach ($sizes as $size)
        {         
            if(!$slugs[$size] instanceof Size)
            {
                continue;
            }
            $sizesArray[$size] = $slugBuilder->buildSlug($slugs[$size]);
        }

        asort($sizesArray,SORT_NATURAL);
        return $sizesArray;
    }
    
    private function prepareProjectsTable($projects)
    {
        $projectArray = [0 => '--None--'];
        foreach ($projects as $project)
        {
            $projectArray[$project->id] = $project->name;
        }
        return $projectArray;
    }
    
    

}
