<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ResponseInterface;

/**
 * Ajax Raw Data Json Response
 */
class RawDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'rawData';
}
