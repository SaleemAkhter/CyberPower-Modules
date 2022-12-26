<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Managers;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Order\VpsComparator;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Managers\Abstracts\ApiManagerBase;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ServiceLocator;
use ModulesGarden\OvhVpsAndDedicated\Core\Helper;

/**
 * Class VpsReinstaller
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class VpsReinstaller extends ApiManagerBase
{
    /**
     * @param $vpsName
     * @param $params
     * @return mixed
     * @throws \Exception
     * @deprecated
     */
    public function reinstall($vpsName, $params)
    {
        return $this->api->vps->one($vpsName)->reinstall($params);
    }

    /**
     * @param $vpsName
     * @return string
     * @throws \Exception
     * @deprecated
     */
    public function resetOption($vpsName)
    {
        $availableOptions = $this->api->vps->one($vpsName)->model()->getSubModel()->getAvailableOptions();
        $activeOptions    = $this->api->vps->one($vpsName)->option()->allToArray();
        $newOptions       = $this->client->getProductConfig()->getOptions();

        foreach ($newOptions as $optionName => $code)
        {
            if($optionName == 'additionalDisk')
            {
                continue;
            }
            if(in_array($optionName, $availableOptions))
            {
                $duration = $this->getOptionDuration($vpsName, $optionName);
                $this->api->order->vps()->one($vpsName)->{$optionName}()->add($duration);

            }
            if(!in_array($optionName, $activeOptions))
            {
                $this->api->vps->one($vpsName)->option()->one($optionName)->remove();
            }
        }
        return 'success';
    }

    private function getOptionDuration($vpsName, $optionName)
    {
        $durations = $this->api->order->vps()->one($vpsName)->{$optionName}()->getPossiblyDurations();
        if(count($durations) < 2)
        {
            return $durations[0];
        }

        $configDuration = $this->client->getProductConfig()->getDuration();
        if(strlen($configDuration) == 1)
        {
            $configDuration = "0{$configDuration}";
        }
        return $configDuration;
    }


    public function reinstallWithSystemCheck($vpsName)
    {
        if($this->isSameSystem())
        {
            return false;
        }

        $comparator = new VpsComparator($this->client);
        $fit        = $comparator->checkSystem($vpsName);
        if (!$fit)
        {
            $lang = ServiceLocator::call('lang');
            throw new \Exception($lang->translate('vpsReinstallError'));
        }


        $this->reinstall($vpsName, $comparator->getReinstallParams());
        $this->resetOption($vpsName);

        Helper\successLog('vpsMachineReinstallSuccess', [
            'params' => $this->client->getParams(),
            'object' => json_encode($this),
            'vpsName' => $vpsName,
        ]);

        return true;
    }

    public function isSameSystem()
    {
        $info = $this->api->vps->one($this->client->getServiceName())->distribution()->model();
        $config = $this->client->getProductConfig();
        list($system, $version) = explode(':', $config->getSystemVersions());
        $isSame = true;
        if($system != $info->getDistribution())
        {
            $isSame = false;
        }
        elseif ($version != $info->getBitFormat())
        {
            $isSame = false;
        }
        elseif ($config->getSystemLanguages() != $info->getLocale())
        {
            $isSame = false;
        }

        return $isSame;
    }
}