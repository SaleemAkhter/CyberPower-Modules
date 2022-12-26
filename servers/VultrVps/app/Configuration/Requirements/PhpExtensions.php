<?php

namespace ModulesGarden\Servers\VultrVps\App\Configuration\Requirements;

/**
 * Required PHP Extensions
 *
 * @author slawomir@modulesgarden.com
 */
class PhpExtensions extends \ModulesGarden\Servers\VultrVps\Core\App\Requirements\Instances\PhpExtensions
{
    protected $requirementsList =
        [
            [
                self::EXTENSION_NAME => 'openssl'
            ],
        ];
}
