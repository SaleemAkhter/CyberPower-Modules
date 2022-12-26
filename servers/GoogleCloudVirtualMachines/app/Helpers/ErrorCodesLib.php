<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\ErrorCodes\ErrorCodes;

class ErrorCodesLib extends ErrorCodes
{
    const IBM_CLOUD_VPS_000001 = [
        self::MESSAGE => 'Invalid Custom Field Relation Id',
        self::CODE => 'IBM_CLOUD_VPS_000001',
    ];

    const IBM_CLOUD_VPS_000002 = [
        self::MESSAGE => 'Invalid Custom Field Type',
        self::CODE => 'IBM_CLOUD_VPS_000002',
    ];

    const IBM_CLOUD_VPS_000003 = [
        self::MESSAGE => 'Invalid Custom Field Name',
        self::CODE => 'IBM_CLOUD_VPS_000003',
    ];

    const IBM_CLOUD_VPS_000004 = [
        self::MESSAGE => 'Invalid Product Id',
        self::CODE => 'IBM_CLOUD_VPS_000004',
    ];

    const IBM_CLOUD_VPS_000005 = [
        self::MESSAGE => 'Invalid Custom Field',
        self::CODE => 'IBM_CLOUD_VPS_000005',
    ];

    const IBM_CLOUD_VPS_000006 = [
        self::MESSAGE => 'Invalid Service Id',
        self::CODE => 'IBM_CLOUD_VPS_000006',
    ];
}
