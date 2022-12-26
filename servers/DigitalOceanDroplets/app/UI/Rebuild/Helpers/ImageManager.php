<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Rebuild\Helpers;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Images\Criteria;
use function ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\isAdmin;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;

/**
 * Description of ImageManager
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ImageManager
{

    protected $params = [];

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function getAllTemplates()
    {
        $api      = new Api($this->params);



        if(isAdmin() === false  && is_null($this->getSelectedImagesList())){
            $crit = new Criteria();
            $crit->setPrivate(false);
            $images   = $api->image->all($crit);
        }else{
            $images   = $api->image->all();
        }

        return $this->preparePrettyTable($images);
    }

    public function getOnlyInitialPurchaseImages( $initialImageSlug )
    {
        $api = new Api($this->params);

        $images = $api->image->all();

        foreach ( $images as $index => $image )
        {
            if ( $image->slug !== $initialImageSlug )
            {
                unset($images[$index]);
            }
        }
        return $this->preparePrettyTable($images);
    }

    private function preparePrettyTable($itemList)
    {
        $allImage = [];
        foreach ($itemList as $item) {
            if (isAdmin() === false) {
                if ($this->checkIsAvailableTemplate($item) === false) {
                    continue;
                }
            }
            $allImage[] = [
                'id' => $item->id,
                'distribution' => $item->distribution,
                'name' => Lang::getInstance()->absoluteT($item->name),
            ];
        }
        return $allImage;
    }

    private function checkIsAvailableTemplate($name){

        $availableTempalte = $this->getSelectedImagesList();
        if(is_null($availableTempalte)){
            return $name->public;
        }
        if(in_array($name->id, $availableTempalte)){
            return true;
        }
        return false;

    }

    private function getSelectedImagesList(){
        $fields = new FieldsProvider($this->params['packageid']);

        return json_decode($fields->getField('clientAreaAvailableImages', []));
    }



    public function rebuildMachine($imageID)
    {
        $api     = new Api($this->params);
        $details = $api->image->one($imageID);
        $api->droplet->rebuild($imageID);
    }

}
