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
final class Projects extends AbstractEntity
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $ownerUuid;

    /**
     * @var string
     */
    public $ownerId;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $purpose;

    /**
     * @var string
     */
    public $environment;

    /**
     * @var boolean
     */
    public $isDefault;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = static::convertDateTime($createdAt);
    }
}
