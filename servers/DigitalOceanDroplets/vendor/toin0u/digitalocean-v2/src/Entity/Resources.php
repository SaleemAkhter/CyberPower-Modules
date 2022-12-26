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
final class Resources extends AbstractEntity
{
    /**
     * @var string
     */
    public $urn;

    /**
     * @var array
     */
    public $links;

    /**
     * @var string
     */
    public $status;

}
