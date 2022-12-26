<?php

namespace ModulesGarden\Servers\AwsEc2\App\Configuration\Requirements;


/**
 * Required Classes
 *
 * @author slawomir@modulesgarden.com
 */
class Classes extends \ModulesGarden\Servers\AwsEc2\Core\App\Requirements\Instances\Classes
{
    protected $requirementsList =
        [
            [
                self::CLASS_NAME => '\Aws\AwsClient'
            ],
        ];
}
