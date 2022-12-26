<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ResponseInterface;

/**
 * Ajax Raw Data Json Response
 */
class RawDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'rawData';
}
