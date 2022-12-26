<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts;

use Ovh\Api;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
/**
 * Class AbstractApiItem
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
abstract class AbstractApiItem extends AbstractApi
{
    protected $itemObject;

    public function __construct(Api $api, Client $client, $path = '', $itemObject = false)
    {
        parent::__construct($api, $client, $path);

        if($itemObject)
        {
            $this->setInfo($itemObject);
        }
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        if(empty($this->itemObject))
        {
            $this->setInfo();
        }

        return $this->itemObject;
    }

    public function setInfo($itemObject = false)
    {
        if($itemObject)
        {
            $this->itemObject = $itemObject;

            return;
        }

        $this->itemObject = $this->get();

    }
}