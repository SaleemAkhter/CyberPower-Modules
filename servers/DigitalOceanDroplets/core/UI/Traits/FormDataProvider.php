<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\FormDataProviderInterface;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper;

/**
 * Form DataProvider related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait FormDataProvider
{
    
    /**
     * Providing save and load data functionalities for Forms
     * @var \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\FormDataProviderInterface
     */
    protected $dataProvider = null;
    protected $providerClass = '';

    public function loadProvider()
    {
        if ($this->providerClass != '' && !is_object($this->dataProvider))
        {
            $this->setProvider(Helper\di($this->providerClass));
        }
        
        return $this;
    }

    /**
     * Sets data provider for Form
     * @return $this
     */
    public function setProvider(FormDataProviderInterface $provider)
    {
//        if(!$this->whmcsParams)
//        {
//            echo '<pre>';
//            die(var_dump($provider));
//        }
        $this->dataProvider = $provider;
        $this->dataProvider->setWhmcsParams($this->whmcsParams);

        return $this;
    }
    
    public function getFormData()
    {
        if($this->dataProvider === null)
        {
            $this->loadProvider();
        }
        
        return $this->dataProvider->getData();
    }
}
