<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Droplets;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\UserData;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Serializer;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\View\Smarty;

/**
 * Description of Create
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Create extends Serializer
{
    /*
     * @var string $name
     */

    protected $name;
    /*
     * @var string $region
     */
    protected $region;
    /*
     * @var string $size
     */
    protected $size;
    /*
     * @var string $image
     */
    protected $image;
    /*
     * @var string $ssh_keys
     */
    protected $ssh_keys = [];
    /*
     * @var boolean $backups
     */
    protected $backups;
    /*
     * @var boolean $ipv6
     */
    protected $ipv6;
    /*
     * @var string $user_data
     */
    protected $user_data;
    /*
     * @var string $private_networking
     */
    protected $private_networking;
    /*
     * @var string $monitoring
     */
    protected $monitoring;
    /*
     * @var string $volumes
     */
    protected $volumes  = [];
    /*
     * @var array $tags
     */
    protected $tags = [];

    public function fill()
    {
        $this->name    = $this->params->getDomain();
        $this->region  = $this->params->getRegion();
        $this->size    = $this->params->getSize();
        $this->image   = (int) $this->params->getImage();
        $this->backups = $this->params->getBackups();
        $this->ipv6    = $this->params->getIpv6();
        $this->ssh_keys = $this->params->getSshKeys();
        
        if (!empty($this->params->getUserData()))
        {
            $template = UserData::read($this->params->getUserData());
            $this->user_data = Smarty::get()->fetch($template, $this->params->getParams());
        }      
        $this->private_networking = $this->params->getPrivateNetworking();
        $this->monitoring         = $this->params->getMonitoring();
        if (!empty($this->params->getTags()))
        {
            $this->tags = $this->params->getTags();
        }
        
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    public function setImage($image)
    {
        $this->image = (int) $image;
        return $this;
    }

    public function setSshKeys($sshKeys)
    {
        $this->ssh_keys = [$sshKeys];
        return $this;
    }
    
    public function addSshKey($key){
        $this->ssh_keys[]   = $key;
    }

    public function setBackups($backups)
    {
        $this->backups = $backups;
        return $this;
    }

    public function setIpv6($ipv6)
    {
        $this->ipv6 = $ipv6;
        return $this;
    }

    public function setUserData($userData = null)
    {
        if ($userData != 0)
        {
            $this->user_data = UserData::read($userData);
        }
        return $this;
    }

    public function setMonitoring($monitoring = null)
    {
        $this->monitoring = $monitoring;
        return $this;
    }

    public function setPrivateNetworking($privateNetworking)
    {
        $this->private_networking = $privateNetworking;
        return $this;
    }

    public function setVolumes($volumes)
    {
        $this->volumes = [$volumes];
        return $this;
    }

    public function setTags($tags = [])
    {
        if (is_array($tags))
        {
            $this->tags = $tags;
        }
        return $this;
    }

}
