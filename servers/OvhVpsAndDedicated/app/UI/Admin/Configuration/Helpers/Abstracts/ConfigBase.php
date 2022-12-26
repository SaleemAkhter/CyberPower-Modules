<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Abstracts;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\Details;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\Ovh;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;

/**
 * Class ConfigBase
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
abstract class ConfigBase
{
    protected static $instance;

    protected $serverDetails;

    protected $productInfo;

    protected $productId;

    /**
     * @var \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api
     */
    protected $api;

    public function __construct($productId = null, $prepareData = true)
    {
        $this->productId = $productId;

        $this->setServerDetails();
        $this->api           = Ovh::API($this->getServerDetails());;

        if($prepareData)
        {
            $this->setBasics();
        }
    }

    public function setBasics()
    {
        //overwrite this
    }

    public function setServerDetails()
    {
        $this->serverDetails = Details::getServerDetails($this->productId);
    }

    public function getServerDetails()
    {
        return $this->serverDetails;
    }

    public function getApi(){
        return $this->api;
    }
}