<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\DataProviders\Providers;

/**
 *
 */
class JsonDataProvider extends \ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider
{

    public function setData($data = [], $params = [])
    {
        $this->data = json_decode($data);
        
        return $this;
    }
}
