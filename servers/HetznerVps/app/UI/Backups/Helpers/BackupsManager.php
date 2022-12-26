<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Backups\Helpers;

use LKDev\HetznerCloud\Models\Images\Image;
use LKDev\HetznerCloud\Models\Backups\ISO;
use ModulesGarden\Servers\HetznerVps\App\Enum\ConfigurableOption;
use ModulesGarden\Servers\HetznerVps\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Models\Images\Criteria;
use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;

/**
 * Description of ImageManager
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class BackupsManager
{

    protected $params = [];

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function read($id)
    {
        $api = new Api($this->params);
        if (empty($api->getClient()->getServerID())) {
            throw new \Exception(Lang::getInstance()->absoluteT('vmIsEmpty'));
        }
        $entity = $api->backups()->get($id);
        if($entity->createdFrom->id != $api->getClient()->getServerID()){
            throw new \Exception(Lang::getInstance()->absoluteT('accessDenied'));
        }
        return $entity;
    }

    public function create($description){

    }

    public function get()
    {
        $api      = new Api($this->params);
        if(empty($api->getClient()->getServerID()))
        {
            throw new \Exception(Lang::getInstance()->absoluteT('vmIsEmpty'));
        }
        return $enteries  = $api->backups()
            ->findByServerId($api->getClient()->getServerID())
            ->fetch();
    }

    public function restore($id)
    {
        $this->read($id);
        $entery = new Image($id);
        $entery->name = $id;
        $api = new Api($this->params);
        if (empty($api->getClient()->getServerID())) {
            throw new \Exception(Lang::getInstance()->absoluteT('vmIsEmpty'));
        }
        return $api->servers()->get($api->getClient()->getServerID())->rebuildFromImage($entery);
    }

    /**
     * @return Image
     * @throws \LKDev\HetznerCloud\APIException
     */
    public function getCurrent()
    {
        $api = new Api($this->params);
        if (empty($api->getClient()->getServerID())) {
            throw new \Exception(Lang::getInstance()->absoluteT('vmIsEmpty'));
        }
        return $api->servers()->get($api->getClient()->getServerID())->image;
    }

    public function delete($id){

    }
}
