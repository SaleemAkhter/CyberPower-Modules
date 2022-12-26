<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Json Data Response
 */
class DataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'data';
}
