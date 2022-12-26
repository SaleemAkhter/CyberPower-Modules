<?php


namespace ModulesGarden\Servers\AwsEc2\App\Config\Packages;

use ModulesGarden\Servers\AwsEc2\App\Helpers\UserData;
use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\App\Models\AvailableImages\Repository;
use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Providers\Config;
use ModulesGarden\Servers\AwsEc2\Core\App\Packages\AppPackageConfiguration;
use ModulesGarden\Servers\AwsEc2\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\AwsEc2\Packages\WhmcsService\Config\Enum;
use ModulesGarden\Servers\AwsEc2\App\Helpers\VolumeType;

class WhmcsService extends AppPackageConfiguration
{
    use RequestObjectHandler;

    const APP_CONFIGURATION =
    [
        self::PACKAGE_STATUS => self::PACKAGE_STATUS_ACTIVE,

        Enum::CUSTOM_FIELDS => [
            [
                Enum::FIELD_NAME => 'InstanceId|Instance ID',
                Enum::FIELD_TYPE => Enum::FIELD_TYPE_TEXT_BOX,
                Enum::FIELD_ADMIN_ONLY => Enum::FIELD_ADMIN_ONLY_ON
            ],
            [
                Enum::FIELD_NAME => 'InstanceTags|Tags',
                Enum::FIELD_TYPE => Enum::FIELD_TYPE_TEXT_BOX,
                Enum::FIELD_ADMIN_ONLY => Enum::FIELD_ADMIN_ONLY_ON
            ],
            [
                Enum::FIELD_NAME => 'sshKey|SSH Public Key',
                Enum::FIELD_TYPE => Enum::FIELD_TYPE_TEXTAREA,
                Enum::FIELD_REG_EXPR => '#ssh-rsa AAAA[0-9A-Za-z+/]+[=]{0,3}( [^@]+@[^@]+)?#',
                Enum::FIELD_SHOW_ORDER => Enum::FIELD_SHOW_ORDER_ON,
                Enum::FIELD_DESCRIPTION => 'Enter your public key in the OpenSSH format here (e.g. ssh-rsa). If not provided key will be automatically generated.',
                Enum::FIELD_REQUIRED => Enum::FIELD_REQUIRED_OFF
            ],
            [
                Enum::FIELD_NAME => 'windowsPassword|Windows Password',
                Enum::FIELD_TYPE => Enum::FIELD_TYPE_TEXT_BOX,
                Enum::FIELD_ADMIN_ONLY => Enum::FIELD_ADMIN_ONLY_ON
            ],
        ],

        Enum::CONFIGURABLE_OPTIONS => [
            [
                Enum::OPTION_NAME => 'region|Region',
                Enum::OPTION_TYPE => Enum::OPTION_TYPE_DROPDOWN,
            ],
            [
                Enum::OPTION_NAME => 'ami|Amazon Machine Image (AMI)',
                Enum::OPTION_TYPE => Enum::OPTION_TYPE_DROPDOWN,
            ],
            [
                Enum::OPTION_NAME => 'instanceType|Instance Type',
                Enum::OPTION_TYPE => Enum::OPTION_TYPE_DROPDOWN,
            ],
            [
                Enum::OPTION_NAME => 'ipv4|Number of IPv4 Addresses',
                Enum::OPTION_TYPE => Enum::OPTION_TYPE_QUANTITY,
            ],
            [
                Enum::OPTION_NAME => 'volumeSize|Volume Size [GB]',
                Enum::OPTION_TYPE => Enum::OPTION_TYPE_QUANTITY,
            ],
            [
                Enum::OPTION_NAME => 'volumeType|Volume Type',
                Enum::OPTION_TYPE => Enum::OPTION_TYPE_DROPDOWN,
            ],
            [
                Enum::OPTION_NAME => 'firewallRules|Number of Firewall Rules',
                Enum::OPTION_TYPE => Enum::OPTION_TYPE_QUANTITY,
            ],
            [
                Enum::OPTION_NAME => 'userDataFile|User Data',
                Enum::OPTION_TYPE => Enum::OPTION_TYPE_DROPDOWN,
            ],
//            [
//                Enum::OPTION_NAME => 'ipv6|Number of IPv6 Addresses',
//                Enum::OPTION_TYPE => Enum::OPTION_TYPE_QUANTITY,
//            ]
        ]
    ];

    public function amiGetSubOptions()
    {
        $pid = $this->getRequestValue('id', 0);

        $amisRepo = new Repository();
        $amis = $amisRepo->getAmisForProduct($pid);
        $subOptions = [];
        foreach($amis as $ami)
        {
            $subOptions[] = [
                Enum::OPTION_SUB_NAME => $ami['image_id'] . '|' . ($ami['description'] ? $ami['description'] : $ami['image_id']),
                Enum::OPTION_SUB_ORDER => Enum::OPTION_SUB_ORDER_DEFAULT,
                Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
            ];
        }
        return $subOptions;
    }

    public function volumeTypeGetSubOptions()
    {
        $subOptions = [];

        foreach(VolumeType::getVolumeTypes() as $item => $elem)
        {
           $subOptions[] = [
                Enum::OPTION_SUB_NAME => $item . '|' . $elem,
                Enum::OPTION_SUB_ORDER => Enum::OPTION_SUB_ORDER_DEFAULT,
                Enum::OPTION_SUB_HIDDEN => $item
            ];
        }

       return $subOptions;
    }

    public function instanceTypeGetSubOptions()
    {
        $configProvider = new Config();
        $instanceTypes = $configProvider->getInstanceTypes();
        $subOptions = [];
        foreach($instanceTypes as $type)
        {
            $subOptions[] = [
                Enum::OPTION_SUB_NAME => $type . '|' . $type,
                Enum::OPTION_SUB_ORDER => Enum::OPTION_SUB_ORDER_DEFAULT,
                Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
            ];
        }

        return $subOptions;
    }

    public function userDataFileGetSubOptions()
    {
        $configProvider = new Config();
        $userDataFiles = UserData::getFilesNames();
        $subOptions = [];
        foreach($userDataFiles as $type)
        {
            $subOptions[] = [
                Enum::OPTION_SUB_NAME => $type . '|' . $type,
                Enum::OPTION_SUB_ORDER => Enum::OPTION_SUB_ORDER_DEFAULT,
                Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
            ];
        }

        return $subOptions;
    }

    public function regionGetSubOptions()
    {
        try
        {
            $productId = $this->getRequestValue('id', 0);

            $awsClient = new ClientWrapper($productId);

            $reqions = $awsClient->getRegions();
        }
        catch (\Aws\Exception\AwsException $exc)
        {
            return [];
        }

        $subOptions = [];
        foreach($reqions as $region)
        {
            $subOptions[] = [
                Enum::OPTION_SUB_NAME => $region['RegionName'] . '|' . $region['RegionName'],
                Enum::OPTION_SUB_ORDER => Enum::OPTION_SUB_ORDER_DEFAULT,
                Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
            ];
        }

        return $subOptions;
    }
}
