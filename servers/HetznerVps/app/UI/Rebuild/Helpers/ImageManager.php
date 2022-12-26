<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Helpers;

use ModulesGarden\Servers\HetznerVps\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Models\Images\Criteria;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\isAdmin;
use ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\Hosting;

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

        $images   = $api->images()->all();

        return $this->preparePrettyTable($images);
    }

    private function preparePrettyTable($itemList)
    {
        $allImage = [];
        $installedImage = $this->getInstalledImage();
        $selected = $this->getSelectedImagesList();
        foreach ($itemList as $item) {
            if(($item->type != 'system') && !$selected){
                continue;
            }
            if (isAdmin() === false) {
                if ($this->checkIsAvailableTemplate($item) === false && $item->id !== $installedImage->id) {
                    continue;
                }
            }

            $allImage[] = [
                'id' => $item->id,
                'distribution' => $item->osFlavor,
                'name' => $item->description,
            ];
        }
        return $allImage;
    }
    private function checkIsAvailableTemplate($name)
    {
        $availableTemplate = $this->getSelectedImagesList();

        if(in_array($name->id, $availableTemplate) || empty($availableTemplate)){
            return true;
        }
        return false;

    }

    private function getSelectedImagesList(){
        $fields = new FieldsProvider($this->params['packageid']);

        return
            !is_array($fields->getField('clientAreaAvailableImages', []))
            ? json_decode($fields->getField('clientAreaAvailableImages', []))
            : $fields->getField('clientAreaAvailableImages', []);
    }

    public function getInstalledImage()
    {
        $api     = new Api($this->params);
        return $api->servers()
            ->get($api->getClient()
            ->getServerID())->image;
    }

    public function rebuildMachine($imageID)
    {
        $api     = new Api($this->params);
        $info =  $api->servers()
            ->get($api->getClient()
            ->getServerID())
            ->rebuildFromImage($this->getImage($imageID));


        $password = $info->getResponse()['root_password'];
        $this->updatePasswordInDB($this->params['serviceid'], $password);

        return $password;
    }


    private function getImage($id)
    {
        $api     = new Api($this->params);
        return $api->images()->get($id);

    }

    private function updatePasswordInDB($hostingID, $password)
    {
        Hosting::where('id', $hostingID)
            ->update([
            'password' => \encrypt($password)
        ]);
    }

    public function getAllTemplatesExceptBackups()
    {
        $api      = new Api($this->params);
        $images   = array_filter($api->images()->all(),function($image){
            if($image->type == 'backup')
                return false;
            return true;
        });

        return $this->preparePrettyTable($images);
    }

}
