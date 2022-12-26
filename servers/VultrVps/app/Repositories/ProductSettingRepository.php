<?php


namespace ModulesGarden\Servers\VultrVps\App\Repositories;

use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

/**
 * Class ProductSettingRepository
 * @package ModulesGarden\Servers\VultrVps\App\Repositories
 * @property string $region
 * @property string $plan
 * @property string $os_id
 * @property string $iso_id
 * @property string $snapshot_id
 * @property string $user_data
 * @property array $changeOsId
 * @property string $app_id
 */
class ProductSettingRepository extends AbstractProductSettingRepository
{

    public function __get($name)
    {
        return $this->get($name);
    }

    public function isEnableIpv6()
    {
        return $this->get("enable_ipv6") == "on";
    }

    public function getBackups()
    {
        return $this->get("backups") == "on" ? "enabled" : "disabled";
    }

    public function hasPermission($conroler){
        return $this->get("permission".ucfirst($conroler)) == "on";
    }

    public function hasPermissionOrFail($conroler){
        if(!$this->hasPermission($conroler)){
            throw new \Exception(sprintf('The page %s could not be accessed',$conroler));
        }

    }

}