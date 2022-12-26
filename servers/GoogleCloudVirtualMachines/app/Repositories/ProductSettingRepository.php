<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory;

/**
 * Class ProductSettingRepository
 * @package ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories
 * @property $region
 * @property $zone
 * @property $image
 * @property $imageProject
 * @property $machineType
 * @property $diskType
 * @property $diskSize
 * @property $network
 * @property $hostnamePrefix
 * @property $networkTier
 * @property $ipv4
 * @property $customMachineType
 * @property $customMachineCpu
 * @property $customMachineRam
 *
 */
class ProductSettingRepository extends AbstractProductSettingRepository
{

    public function getMachineTypeAsPath(){
        $poject = (new ProjectFactory())->fromParams();
        return sprintf("projects/%s/zones/%s/machineTypes/%s", $poject, $this->zone, $this->machineType);
    }

    public function getSourceImageAsPath(){
        $project = $this->imageProject;

        if($this->imageProject === 'custom-images'){
            $project = (new ProjectFactory())->fromParams();
        }

        return sprintf("projects/%s/global/images/%s", $project, $this->image);
    }


    public function getDiskTypeAsPath(){
        $poject = (new ProjectFactory())->fromParams();
        return sprintf("projects/%s/zones/%s/diskTypes/%s",$poject, $this->zone, $this->diskType);
    }


    public function getNetworkAsPath(){
        $poject = (new ProjectFactory())->fromParams();
        return sprintf("projects/%s/regions/%s/subnetworks/%s",$poject, $this->region, $this->network);
    }



    public function isIpv4(){
        return $this->ipv4 == "on";
    }

    public function isNetworkTierPremium(){
        return $this->networkTier == "PREMIUM";
    }

    public function isNetworkTierStandard(){
        return $this->networkTier == "STANDARD";
    }
}