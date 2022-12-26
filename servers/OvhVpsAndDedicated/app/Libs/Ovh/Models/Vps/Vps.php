<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Serializer;


/**
 * Class Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Vps extends Serializer
{
    /**
     * @var
     */
    protected $cluster;
    /**
     * @var
     */
    protected $memoryLimit;
    /**
     * @var
     */
    protected $netbootMode;
    /**
     * @var
     */
    protected $zone;
    /**
     * @var
     */
    protected $name;


    /**
     * @var SubModel
     */
    protected $model;

    /**
     * @var
     */
    protected $monitoringIpBlocks;

    /**
     * @var
     */
    protected $keymap;
    /**
     * @var
     */
    protected $state;
    /**
     * @var
     */
    protected $vcore;
    /**
     * @var
     */
    protected $offerType;
    /**
     * @var
     */
    protected $slaMonitoring;
    /**
     * @var
     */
    protected $displayName;

    /**
     * Vps constructor.
     * @param $params
     */
    public function __construct($params)
    {
        $this->fill($params);
        $this->setSubModel(new SubModel($params['model']));
    }

    public function getFullProductOffer()
    {
        return $this->offerType ."_".  $this->getSubModel()->getName() ."_". $this->getSubModel()->getVersion();
    }

    /**
     * @return mixed
     */
    public function getCluster()
    {
        return $this->cluster;
    }

    /**
     * @param mixed $cluster
     * @return Vps
     */
    public function setCluster($cluster)
    {
        $this->cluster = $cluster;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMemoryLimit()
    {
        return $this->memoryLimit;
    }

    /**
     * @param mixed $memoryLimit
     * @return Vps
     */
    public function setMemoryLimit($memoryLimit)
    {
        $this->memoryLimit = $memoryLimit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNetbootMode()
    {
        return $this->netbootMode;
    }

    /**
     * @param mixed $netbootMode
     * @return Vps
     */
    public function setNetbootMode($netbootMode)
    {
        $this->netbootMode = $netbootMode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * @param mixed $zone
     * @return Vps
     */
    public function setZone($zone)
    {
        $this->zone = $zone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Vps
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return SubModel
     */
    public function getSubModel()
    {
        return $this->model;
    }

    /**
     * @param SubModel $model
     * @return Vps
     */
    public function setSubModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMonitoringIpBlocks()
    {
        return $this->monitoringIpBlocks;
    }

    /**
     * @param mixed $monitoringIpBlocks
     * @return Vps
     */
    public function setMonitoringIpBlocks($monitoringIpBlocks)
    {
        $this->monitoringIpBlocks = $monitoringIpBlocks;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKeymap()
    {
        return $this->keymap;
    }

    /**
     * @param mixed $keymap
     * @return Vps
     */
    public function setKeymap($keymap)
    {
        $this->keymap = $keymap;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     * @return Vps
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVcore()
    {
        return $this->vcore;
    }

    /**
     * @param mixed $vcore
     * @return Vps
     */
    public function setVcore($vcore)
    {
        $this->vcore = $vcore;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOfferType()
    {
        return $this->offerType;
    }

    /**
     * @param mixed $offerType
     * @return Vps
     */
    public function setOfferType($offerType)
    {
        $this->offerType = $offerType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlaMonitoring()
    {
        return $this->slaMonitoring;
    }

    /**
     * @param mixed $slaMonitoring
     * @return Vps
     */
    public function setSlaMonitoring($slaMonitoring)
    {
        $this->slaMonitoring = $slaMonitoring;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param mixed $displayName
     * @return Vps
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }
}