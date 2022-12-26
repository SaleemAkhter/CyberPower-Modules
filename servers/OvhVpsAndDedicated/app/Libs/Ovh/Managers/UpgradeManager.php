<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Managers;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Managers\Abstracts\ApiManagerBase;
use ModulesGarden\OvhVpsAndDedicated\Core\Helper;

/**
 * Class VpsReinstaller
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class UpgradeManager extends ApiManagerBase
{
    /**
     * Available Options
     *
     * @var array
     */
    private $availableUpgradeOptions = [];

    /**
     *  Using Option
     *
     * @var null
     */
    private $currentOption;

    /**
     * Upgrading Option From WHMCS
     *
     * @var
     */
    private $upgradeOption;

    /**
     * Upgrading Option From OVH
     */
    private $optionAvailableToUpgrade;


    /**
     * Main Method
     *
     * @throws \Exception
     */
    public function run()
    {
        $this->loadCurrentOffer();
        $this->loadAvailableUpgradeOptions();
        $this->upgradeOption = $this->client->getProductConfig()->getProduct();


        $this->processOptions();

        if(!$this->goUpgrade())
        {
            return;
        }
        $this->processProduct();

    }

    public function processOptions()
    {
        $availableOptions = $this->api->vps->one($this->client->getServiceName())->model()->getSubModel()->getAvailableOptions();


        $vpsOrder = $this->api->order->vps()->one($this->client->getServiceName());

        foreach ($this->client->getProductConfig()->getEnabledOptionsToUpgrade() as $option)
        {
            if(!in_array($option, $availableOptions))
            {
                continue;
            }
            try
            {
                if(!method_exists($vpsOrder, $option))
                {
                    \logModuleCall('OVH Method Not Exists', "Method ". $option);
                    continue;
                }
                $duration = $vpsOrder->{$option}()->getPossiblyDurations()[0];
                $response = $vpsOrder->{$option}()->addOption($duration);
            }
            catch (\Exception $exception)
            {
                Helper\errorLog('vpsUpgradeDowngradeOptionError', [
                    'params' => $this->client->getParams(),
                    'option' => $option,
                    'object' => json_encode($this)
                ], [
                    'message' => $exception->getMessage()
                ]);
            }
        }
    }

    public function processProduct()
    {
        $modelName = $this->optionAvailableToUpgrade['name'];
        $orderUpgrade = $this->api->order->vps()->one($this->client->getServiceName())->upgrade();
        $duration = $orderUpgrade->getDurations($modelName)[0];
        $result = $orderUpgrade->run($duration, $modelName);

        Helper\successLog('vpsUpgradeDowngradeProductSuccess', [
                'model' =>  $modelName,
                'duration' => $duration,
                'params' => $this->client->getParams(),
                'object' => json_encode($this)
        ]);

        return $result;
    }

    /**
     * Validate Options before upgrade process
     *
     * @return bool
     */
    public function goUpgrade()
    {
        if(!$this->isCorrectOfferForServerType())
        {
            Helper\infoLog('unableToUpgradeDowngradeIsNotCorrectForUsingServer', [
                'params' => $this->client->getParams(),
                'object' => json_encode($this)
            ]);
            return false;
        }
        elseif($this->isSameOffer())
        {
            Helper\infoLog('unableToUpgradeDowngradeUsageOptionIsSameAsSelected', [
                'params' => $this->client->getParams(),
                'object' => json_encode($this)
            ]);
            return false;
        }
        elseif (!$this->isUpgradeOptionAvailable())
        {
            return false;
        }

        return true;
    }

    /**
     * Load Available options from API to upgrade
     *
     * @throws \Exception
     */
    private function loadAvailableUpgradeOptions()
    {
        $this->availableUpgradeOptions = $this->api->vps->one($this->client->getServiceName())->availableUpgrade();
    }

    /**
     * Load Current usage offer
     *
     * @throws \Exception
     */
    private function loadCurrentOffer()
    {
        $this->currentOption = $this->api->vps->one($this->client->getServiceName())->model()->getFullProductOffer();
    }

    /**
     *
     * If offer is the same, there is nothing to upgrade
     *
     * @return bool
     */
    private function isSameOffer()
    {
        return "vps_". $this->currentOption == $this->upgradeOption;
    }

    /**
     *
     * Check if new options is available for machine
     *
     * @return bool
     */
    private function isUpgradeOptionAvailable()
    {
        list($vps, $vpsType, $modelName, $version) = explode('_', $this->upgradeOption);
        foreach ($this->availableUpgradeOptions as $option)
        {
            if($option['name'] != $modelName)
            {
                continue;
            }
            elseif($option['version'] != $version)
            {
                continue;
            }
            $this->optionAvailableToUpgrade = $option;
            return true;
        }

        Helper\infoLog('vpsUnableUpgradeDowngradeSelectedOptionIsNotInAvailableOptions', [
            'params' => $this->client->getParams(),
            'object' => json_encode($this)
        ]);

        return false;
    }

    /**
     * Check if options is correct for example SSD = SSD
     *
     * @return bool
     */
    private function isCorrectOfferForServerType()
    {
        $vpsTypeCurrent = explode('_', $this->currentOption)[0];
        $vpsTypeUpgrade = explode('_', $this->upgradeOption)[1];

        return $vpsTypeCurrent == $vpsTypeUpgrade;
    }
}
