<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Serializer;

/**
 * Class IpmiFeatures
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class IpmiFeatures extends Serializer
{
    protected  $supportedFeatures = [];

    protected  $activated;

    public function __construct($params)
    {
        $this->fill($params);
    }


    public function getEnabledSupportedFeatures()
    {
        if(!$this->activated)
        {
            return [];
        }
        $out = [];
        foreach ($this->supportedFeatures as $key => $supportedFeature)
        {
            if((bool) $supportedFeature == false)
            {
                continue;
            }
            $out[$key] = $supportedFeature;
        }

        return $out;
    }

    /**
     * @return array
     */
    public function getSupportedFeatures()
    {
        return $this->supportedFeatures;
    }

    /**
     * @param array $supportedFeatures
     */
    public function setSupportedFeatures($supportedFeatures)
    {
        $this->supportedFeatures = $supportedFeatures;
    }

    /**
     * @return mixed
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * @param mixed $activated
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;
    }
}
