<?php

namespace ModulesGarden\Servers\AwsEc2\Core\Interfaces;

/**
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
interface CurlParser
{
    /**
     * @param string $head
     * @param int $size
     * @return array array($header, $body)
     */
    public function rebuild($head, $size);
}
