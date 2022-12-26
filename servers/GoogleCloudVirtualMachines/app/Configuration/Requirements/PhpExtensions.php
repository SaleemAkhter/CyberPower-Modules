<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Configuration\Requirements;

/**
 * Required PHP Extensions
 *
 * @author slawomir@modulesgarden.com
 */
class PhpExtensions extends \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Requirements\Instances\PhpExtensions
{
    protected $requirementsList =
    [
        [
            self::EXTENSION_NAME => 'openssl'
        ],
    ];
}
