<?php
namespace ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Helpers;
use LKDev\HetznerCloud\Models\Datacenters\Datacenter;
use LKDev\HetznerCloud\Models\Images\Image;
use LKDev\HetznerCloud\Models\Locations\Location;
use LKDev\HetznerCloud\Models\Servers\Server;
use LKDev\HetznerCloud\Models\Servers\Types\ServerType;
use ModulesGarden\Servers\HetznerVps\App\Helpers\UserData;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;
use \Exception;
use ModulesGarden\Servers\HetznerVps\Core\Http\View\Smarty;
use ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\Hosting;

/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 25.03.2019
 * Time: 14:53
 */

class CreateVM
{

    protected $api;

    protected $model = [];
    private $whmcsParams;

    public function __construct($params)
    {
        $this->api = new Api($params);
        $this->whmcsParams = $params;
    }

    public function create()
    {
        $this->createModel();
        $this->validateVolumeSize();
        $this->addSshKeyIfIsSet();

        if($this->api->getClient()->getDatacenter() != 0)
        {
            $server = $this->createInDataCenter();
        }
        else
        {
            $server = $this->createInLocation();
        }

        $response = $server->getResponse();

        Hosting::where('id', $this->api->getClient()->getHostingID())->update([
            'password' => empty($this->model['sshKey']) ? \encrypt($response['root_password']) : '',
            'username' => 'root',
            'dedicatedip' => $response['server']->publicNet->ipv4->ip,
            'assignedips' => $response['server']->publicNet->ipv6->ip,
        ]);
        $serverID = $response['action']->resources[0]->id;
        $this->createVolume($serverID);

        return $serverID;
    }
    private function addSshKeyIfIsSet()
    {
        $this->model['sshKey'] = [];

        if($this->api->getClient()->getSshKey() != "")
        {
            try
            {
                    $key = $this->api->sshKeys()->create($this->api->getClient()->getDomain(), $this->api->getClient()->getSshKey());
                    $this->model['sshKey'] = [$key->id];

            }
            catch (Exception $ex)
            {
                throw new Exception(Lang::getInstance()->absoluteT('cannotCreateKey'));
            }

        }
    }

    private function validateVolumeSize()
    {
        if($this->api->getClient()->getVolume() > 0 && $this->api->getClient()->getVolume() < 10)
        {
            throw new Exception(Lang::getInstance()->absoluteT('volumeMustBeHigherThanTen'));
        }
    }

    private function createVolume($serverID)
    {
        try
        {
            if($this->api->getClient()->getVolume() > 0)
            {
                sleep(2);
                $volume = $this->api->volumes()->getByname($this->model['name']);
                if($volume) {
                    $responseVolumeID = $volume->id;
                } else {
                    $response = $this->api->volumes()->create($this->model['name'], $this->api->getClient()->getVolume(), new Server($serverID));
                    $responseVolumeID = $response->getResponse()['volume']->id;
                }
                /**
                 * Add cron task to attach volume
                 */
                \ModulesGarden\Servers\HetznerVps\App\Service\CronTask\RegisterTask::attachVolume($this->api->getClient()->getWhmcsServerID(), $serverID, $responseVolumeID);
            }
        }
        catch (Exception $ex)
        {
            $this->removeKey();

            $this->removeMachine($serverID);

            throw new Exception(Lang::getInstance()->absoluteT('cannotCreateVolume'));
        }

    }

    private function createModel()
    {
        $this->model['name'] = $this->api->getClient()->getDomain();
        $this->model['type'] = $this->getServerType();
        $this->model['image'] = $this->getImage();

        $template = UserData::read($this->api->getClient()->getUserData());
        $this->model['userData'] = Smarty::get()->fetchString($template, $this->whmcsParams);
        $this->addDatacenterAndLocation();
    }

    private function getServerType()
    {
         return new ServerType($this->api->getClient()->getType());
    }

    private function getImage()
    {
         return new Image($this->api->getClient()->getImage());
    }

    private function addDatacenterAndLocation()
    {
        $this->model['location']  = new Location($this->api->getClient()->getLocation(), '','','','',0,0);
        $this->model['datacenter']  = new Datacenter($this->api->getClient()->getDatacenter(), '', '', $this->model['location']);
    }

    private function removeKey()
    {
        if(!empty($this->model['sshKey']))
        {
            $this->api->sshKeys()->get(reset($this->model['sshKey']))->delete();
        }
    }
    private function removeMachine($serverID)
    {
        $this->api->servers()->get($serverID)->delete();
    }

    private function createInLocation()
    {
       return  $this->api->servers()->createInLocation($this->model['name'], $this->model['type'], $this->model['image'], $this->model['location'], $this->model['sshKey'], true, $this->model['userData']);
    }

    private function createInDataCenter()
    {
        return  $this->api->servers()->createInDatacenter($this->model['name'], $this->model['type'], $this->model['image'], $this->model['datacenter'], $this->model['sshKey'], true, $this->model['userData']);
    }
}