<?php

namespace ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\ResponseInterface;

/**
 * Ajax Raw Data Json Response
 */
class RawDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'rawData';
}
