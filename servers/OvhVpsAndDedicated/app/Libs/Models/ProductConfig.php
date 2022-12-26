<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Models\Product;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Order\Basics;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Serializer;

/**
 * Class ProductConfig
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ProductConfig extends Serializer
{

    const WINDOWS = 'windows';
    const SNAPSHOT = 'snapshot';
    const FTPBACKUP = 'ftpbackup';
    const AUTOMATED_BACKUP = 'automatedBackup';
    const CPANEL = 'cpanel--hostingsuite';
    const PLESK = 'plesk';


    private $whmcsProductId;
    private $product;
    private $systemVersions;
    private $systemLanguages;
    private $localization;
    private $pleskLicense;
    private $duration;

    private $license;

    private $cpanelLicense   = false;
    private $snapshot        = false;
    private $automatedBackup = false;
    private $ftpBackupSpace  = false;
    private $windows         = false;


    public function __construct($params)
    {
        $this->whmcsProductId = $params['packageid'];

        $config                = $params['configoptions'];
        if($config['planCodeVpsOsAddon']){
            list($planCode, $os, $addon) = explode(":", $config['planCodeVpsOsAddon']);
        }
        $product              = new FieldsProvider($this->whmcsProductId);

        $this->product         = isset($config['product']) ? $config['product'] : $product->getField('vpsProduct');
        if($planCode ){
            $this->product   = $planCode ;
        }
        $this->systemVersions  = isset($config['systemVersions']) ? $config['systemVersions'] : $product->getField('vpsOs');
        if($os){
            $this->systemVersions  =  $os;
        }
        $this->systemLanguages = isset($config['systemLanguage']) ? $config['systemLanguage'] : $product->getField('vpsLanguage');
        $this->localization    = isset($config['localization']) ? $config['localization'] : $product->getField('vpsLocalizations');
        $this->duration        = isset($config['duration']) ? $config['duration'] : $product->getField('vpsDuration');

        $this->pleskLicense    = isset($config['pleskLicense']) ? $config['pleskLicense'] : $product->getField('vpsPlesk');
        $this->cpanelLicense   = isset($config['cpanelLicense']) ? $config['cpanelLicense'] : $product->getField('vpsCpanel');
        $this->snapshot        = isset($config['snapshot']) ? $config['snapshot'] : $product->getField('vpsSnapshot');
        $this->ftpBackupSpace  = isset($config['ftpBackupSpace']) ? $config['ftpBackupSpace'] : $product->getField('vpsFtpbackup');
        $this->automatedBackup = isset($config['automatedBackup']) ? $config['automatedBackup'] : $product->getField('vpsAutomatedBackup');
        $this->windows         = isset($config['windows']) ? $config['windows'] : $product->getField('vpsWindows');
        $this->license         = isset($config['license']) ? $config['license'] : false; //new Option



    }


    public function getFullOperatingSystem()
    {
        $system = Basics::getSystemNameByVersion($this->getSystemVersions());
        list($version, $bits) = explode(':', $this->getSystemVersions());

        return "{$system}--{$version}--{$bits}--{$this->getSystemLanguages()}";
    }


    public function getOptions()
    {
        $out = [];
        if ($this->snapshot)
        {
            $out[self::SNAPSHOT] = $this->getProductPlanToOption() . self::SNAPSHOT;
        }

        if ($this->ftpBackupSpace)
        {
            $out[self::FTPBACKUP] = $this->getProductPlanToOption() . self::FTPBACKUP;
        }
        if ($this->automatedBackup)
        {
            $out[self::AUTOMATED_BACKUP] = $this->getProductPlanToOption() . self::AUTOMATED_BACKUP;
        }
        if ($this->license)
        {
            $licenseAvailable = ['cpanel', 'plesk', 'windows'];


            foreach ($licenseAvailable as $licenseName)
            {
                if (stripos($this->license, $licenseName) !== false)
                {
                    $out[$licenseName] = $this->getProductPlanToOption() . $this->license;;
                    break;
                }
            }
        }
        else
        {
            if ($this->cpanelLicense)
            {
                $out[self::CPANEL] = $this->getProductPlanToOption() . self::CPANEL;
            }
            if ($this->windows)
            {
                $out[self::WINDOWS] =  $this->getProductPlanToOption() . self::WINDOWS;
            }
            if ($this->pleskLicense)
            {
                $out[self::PLESK] =  $this->getProductPlanToOption() . $this->getPleskLicense();
            }
        }


        return $out;
    }

    public function getEnabledOptionsToUpgrade()
    {
        $out = [];
        if ($this->snapshot)
        {
            $out[self::SNAPSHOT] =  self::SNAPSHOT;
        }

        if ($this->ftpBackupSpace)
        {
            $out[self::FTPBACKUP] =  self::FTPBACKUP;
        }
        if ($this->automatedBackup)
        {
            $out[self::AUTOMATED_BACKUP] =  self::AUTOMATED_BACKUP;
        }



        if(!$this->license)
        {
            if ($this->cpanelLicense)
            {
                $out[self::CPANEL] = self::CPANEL;
            }
            if ($this->windows)
            {
                $out[self::WINDOWS] =  self::WINDOWS;
            }
            if ($this->pleskLicense)
            {
                $out[self::PLESK] =  $this->getPleskLicense();
            }

            return $out;
        }

        $licenseAvailable = ['cpanel', 'plesk', 'windows'];


        foreach ($licenseAvailable as $licenseName)
        {
            if (stripos($this->license, $licenseName) !== false)
            {
                $out[$licenseName] = $licenseName;
                break;
            }
        }

        return $out;
    }

    private function getProductPlanToOption()
    {
        $explodedProductPlan = explode('_', $this->product);
        list($vps, $type, $model, $ver) = $explodedProductPlan;

        return "{$vps}_{$type}_addon_{$model}_";
    }

    /**
     * @return bool|string
     */
    public function getSnapshot()
    {
        return $this->snapshot;
    }

    /**
     * @param bool|string $snapshot
     */
    public function setSnapshot($snapshot)
    {
        $this->snapshot = $snapshot;
    }

    /**
     * @return bool|string
     */
    public function getAutomatedBackup()
    {
        return $this->automatedBackup;
    }

    /**
     * @param bool|string $automatedBackup
     */
    public function setAutomatedBackup($automatedBackup)
    {
        $this->automatedBackup = $automatedBackup;
    }

    /**
     * @return bool|string
     */
    public function getFtpBackupSpace()
    {
        return $this->ftpBackupSpace;
    }

    /**
     * @param bool|string $ftpBackupSpace
     */
    public function setFtpBackupSpace($ftpBackupSpace)
    {
        $this->ftpBackupSpace = $ftpBackupSpace;
    }

    /**
     * @return bool|string
     */
    public function getWindows()
    {
        return $this->windows;
    }

    /**
     * @param bool|string $windows
     */
    public function setWindows($windows)
    {
        $this->windows = $windows;
    }


    /**
     * @return mixed
     */
    public function getWhmcsProductId()
    {
        return $this->whmcsProductId;
    }

    /**
     * @param mixed $whmcsProductId
     */
    public function setWhmcsProductId($whmcsProductId)
    {
        $this->whmcsProductId = $whmcsProductId;
    }

    /**
     * @return string
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param string $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return string
     */
    public function getSystemVersions()
    {
        return $this->systemVersions;
    }

    /**
     * @param string $systemVersions
     */
    public function setSystemVersions($systemVersions)
    {
        $this->systemVersions = $systemVersions;
    }

    /**
     * @return string
     */
    public function getSystemLanguages()
    {
        return $this->systemLanguages;
    }

    /**
     * @param string $systemLanguages
     */
    public function setSystemLanguages($systemLanguages)
    {
        $this->systemLanguages = $systemLanguages;
    }

    /**
     * @return string
     */
    public function getLocalization()
    {
        return $this->localization;
    }

    /**
     * @param string $localization
     */
    public function setLocalization($localization)
    {
        $this->localization = $localization;
    }

    /**
     * @return string
     */
    public function getPleskLicense()
    {
        return $this->pleskLicense;
    }

    /**
     * @param string $pleskLicense
     */
    public function setPleskLicense($pleskLicense)
    {
        $this->pleskLicense = $pleskLicense;
    }

    /**
     * @return string
     */
    public function getCpanelLicense()
    {
        return $this->cpanelLicense;
    }

    /**
     * @param string $cpanelLicense
     */
    public function setCpanelLicense($cpanelLicense)
    {
        $this->cpanelLicense = $cpanelLicense;
    }

    /**
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param string $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return bool
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * @param bool $license
     */
    public function setLicense($license)
    {
        $this->license = $license;
    }



}
