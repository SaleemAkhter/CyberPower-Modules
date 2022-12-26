<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\Interfaces;

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
