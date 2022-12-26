<?php

namespace ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Json Data Response
 */
class DataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'data';
}
