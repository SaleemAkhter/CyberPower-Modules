<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Isos\Helpers;

use LKDev\HetznerCloud\Models\ISOs\ISO;
use LKDev\HetznerCloud\RequestOpts;
use ModulesGarden\Servers\HetznerVps\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Models\Images\Criteria;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\isAdmin;
use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;
use ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\Hosting;

/**
 * Description of ImageManager
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class IsoManager
{

    protected $params = [];

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function read($id){
        $api      = new Api($this->params);
        return $api->isos()->get($id);
    }

    public function get()
    {
        $api      = new Api($this->params);
        $images   = $api->isos()->all(new RequestOpts(50));
        $selected = $this->getClientAreaAvailableIsos();
        $enteries=[];
        foreach ($images  as $image) {
            if($selected && !in_array($image->id, $selected)){
                continue;
            }
            $enteries[]=[
                'id' => $image->id,
                "name" => $image->name,
                 "description" => $image->description,
            ];
        }
        return $enteries;
    }

    public function attachIso($id){
        $iso  = $this->read($id);
        $api      = new Api($this->params);
        if(empty($api->getClient()->getServerID()))
        {
            throw new \Exception(Lang::getInstance()->absoluteT('vmIsEmpty'));
        }
        return $api->servers()->get($api->getClient()->getServerID())->attachISO($iso);
    }

    public function unmount(){
        $api      = new Api($this->params);
        if(empty($api->getClient()->getServerID()))
        {
            throw new \Exception(Lang::getInstance()->absoluteT('vmIsEmpty'));
        }
        return $api->servers()->get($api->getClient()->getServerID())->detachISO();
    }

    /**
     * @return ISO|null
     * @throws \Exception
     */
    public function getIsoMounted(){
        $api      = new Api($this->params);
        if(empty($api->getClient()->getServerID()))
        {
            throw new \Exception(Lang::getInstance()->absoluteT('vmIsEmpty'));
        }
        return $api->servers()->get($api->getClient()->getServerID())->iso;
    }

    private function getClientAreaAvailableIsos(){
        $fields = new FieldsProvider($this->params['packageid']);
        return
            !is_array($fields->getField('clientAreaAvailableIsos', []))
            ? (array) json_decode($fields->getField('clientAreaAvailableIsos', []))
            : $fields->getField('clientAreaAvailableIsos', []);
    }


}
