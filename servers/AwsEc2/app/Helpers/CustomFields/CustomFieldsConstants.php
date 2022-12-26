<?php

namespace ModulesGarden\Servers\AwsEc2\App\Helpers\CustomFields;


class CustomFieldsConstants
{
    const INSTANCE_ID = 'InstanceId';

    const PROV_CUSTOM_FIELD_TYPE = 'product';

    public static function getCustomFieldsList()
    {
        return [self::INSTANCE_ID];
    }
    public static function getCustomFieldsTypesList()
    {
        return [self::PROV_CUSTOM_FIELD_TYPE];
    }
}
