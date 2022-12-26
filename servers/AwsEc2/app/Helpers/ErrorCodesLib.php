<?php

namespace ModulesGarden\Servers\AwsEc2\App\Helpers;

use \ModulesGarden\Servers\AwsEc2\Core\HandlerError\ErrorCodes\ErrorCodes;

class ErrorCodesLib extends ErrorCodes
{
    const AWS_EC2_000001 = [
        self::MESSAGE => 'Invalid Custom Field Relation Id',
        self::CODE => 'AWS_EC2_000001',
    ];

    const AWS_EC2_000002 = [
        self::MESSAGE => 'Invalid Custom Field Type',
        self::CODE => 'AWS_EC2_000002',
    ];

    const AWS_EC2_000003 = [
        self::MESSAGE => 'Invalid Custom Field Name',
        self::CODE => 'AWS_EC2_000003',
    ];

    const AWS_EC2_000004 = [
        self::MESSAGE => 'Invalid Product Id',
        self::CODE => 'AWS_EC2_000004',
    ];

    const AWS_EC2_000005 = [
        self::MESSAGE => 'Invalid Custom Field',
        self::CODE => 'AWS_EC2_000005',
    ];

    const AWS_EC2_000006 = [
        self::MESSAGE => 'Invalid Service Id',
        self::CODE => 'AWS_EC2_000006',
    ];
}
