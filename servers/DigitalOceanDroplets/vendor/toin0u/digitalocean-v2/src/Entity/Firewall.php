<?php

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Entity;

/**
 * @author  Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
final class Firewall extends AbstractEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $pendingChanges;

    /**
     * @var array
     */
    public $inboundRules;

    /**
     * @var array
     */
    public $outboundRules;

    /**
     * @var array
     */
    public $dropletIds;

    /**
     * @var array
     */
    public $tags;

    /**
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = static::convertDateTime($createdAt);
    }
}
