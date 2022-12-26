<?php

namespace ModulesGarden\Servers\HetznerVps\Core\Api\AbstractApi;

use ModulesGarden\Servers\HetznerVps\Core\Interfaces\CurlParser;

/**
 * Description of Parser
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Parser implements CurlParser
{

    public function rebuild($head, $size)
    {
        return [
            substr($head, 0, $size),
            substr($head, $size)
        ];
    }
}
