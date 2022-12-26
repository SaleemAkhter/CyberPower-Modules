<?php

namespace ModulesGarden\Servers\HetznerVps\App\Enum;

final class ConfigurableOption
{
    const SNAPSHOTS = "snapshots";
    const VOLUME = 'volume';
    const TYPE = 'type';
    const IMAGE = 'image';
    const DATACENTER = 'datacenter';
    const LOCATION = 'location';
    const BACKUPS = 'backups';
    const NUMBER_OF_FLOATING_IPS = 'Number of Floating IPs';
}