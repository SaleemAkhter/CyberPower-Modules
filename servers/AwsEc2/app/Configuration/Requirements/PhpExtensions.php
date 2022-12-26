<?php

namespace ModulesGarden\Servers\AwsEc2\App\Configuration\Requirements;

/**
 * Required PHP Extensions
 *
 * @author slawomir@modulesgarden.com
 */
class PhpExtensions extends \ModulesGarden\Servers\AwsEc2\Core\App\Requirements\Instances\PhpExtensions
{
    protected $requirementsList =
    [
        [
            self::EXTENSION_NAME => 'openssl'
        ],
    ];
}
