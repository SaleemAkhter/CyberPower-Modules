<?php


namespace ModulesGarden\Servers\AwsEc2\App\Helpers;


class VolumeType
{
    const VOLUME_TYPES = [
        'gp3'       => 'General Purpose SSD (gp3)',
        'gp2'       => 'General Purpose SSD (gp2)',
        'io2'       => 'Provisioned IOPS SSD (io2)',
        'io1'       => 'Provisioned IOPS SSD (io1)',
        'standard'  => 'Magnetic',
        'sc1'       => 'Cold HDD (sc1)',
        'st1'       => 'Throughput Optimized HDD (st1)'
    ];

    public static function getVolumeTypes()
    {
        return self::VOLUME_TYPES;
    }
}
