<?php


namespace ModulesGarden\Servers\HetznerVps\App\Config\Packages;

use ModulesGarden\Servers\HetznerVps\App\Helpers\UserData;
use ModulesGarden\Servers\HetznerVps\App\Service\Sidebar\ProductService;
use ModulesGarden\Servers\HetznerVps\Core\App\Packages\AppPackageConfiguration;
use ModulesGarden\Servers\HetznerVps\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\HetznerVps\Core\UI\Traits\WhmcsParams;
use ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\Config\Enum;

class WhmcsService extends AppPackageConfiguration
{
    use RequestObjectHandler;
    use WhmcsParams;
    use ProductService;

    const APP_CONFIGURATION =
        [
            self::PACKAGE_STATUS => self::PACKAGE_STATUS_ACTIVE,

            Enum::CUSTOM_FIELDS => [
                [
                    Enum::FIELD_NAME => 'serverID|Server ID',
                    Enum::FIELD_TYPE => Enum::FIELD_TYPE_TEXT_BOX,
                    Enum::FIELD_ADMIN_ONLY => Enum::FIELD_ADMIN_ONLY_ON
                ],
                [
                    Enum::FIELD_NAME => 'sshkeys|SSH Public Key',
                    Enum::FIELD_TYPE => Enum::FIELD_TYPE_TEXTAREA,
                    Enum::FIELD_ADMIN_ONLY => Enum::FIELD_ADMIN_ONLY_ON,
                    Enum::FIELD_REG_EXPR => '#ssh-rsa AAAA[0-9A-Za-z+/]+[=]{0,3}( [^@]+@[^@]+)?#',
                    Enum::FIELD_SHOW_ORDER => Enum::FIELD_SHOW_ORDER_ON,
                    Enum::FIELD_DESCRIPTION => 'Enter your public key in the OpenSSH format here (e.g. ssh-rsa).'
                ]
            ],
        ];

    public function locationGetSubOptions()
    {
        $subOptions = [];
        $locations = new \LKDev\HetznerCloud\Models\Locations\Locations();
        foreach ($locations->all() as $key => $value){
            $subOptions[] = [
                Enum::OPTION_SUB_NAME => $value->name.'|'.$value->description,
                Enum::OPTION_SUB_OPTION_ID => $value->id,
                Enum::OPTION_SUB_ORDER => Enum::OPTION_SUB_ORDER_DEFAULT,
            ];
        }
        return $subOptions;
    }

    public function datacenterGetSubOptions()
    {
        $subOptions = [];
        $datacenters = new \LKDev\HetznerCloud\Models\Datacenters\Datacenters();
        foreach ($datacenters->all() as $key => $value){
            $subOptions[] = [
                Enum::OPTION_SUB_NAME => $value->name.'|'.$value->description,
                Enum::OPTION_SUB_OPTION_ID => $value->id,
            ];
        }
        return $subOptions;
    }

    public function imageGetSubOptions()
    {
        $subOptions = [];
        $images = new \LKDev\HetznerCloud\Models\Images\Images();
        foreach ($images->all() as $key => $value){
            $subOptions[] = [
                Enum::OPTION_SUB_NAME => $value->name.'|'.$value->description,
                Enum::OPTION_SUB_OPTION_ID => $value->id,
            ];
        }
        return $subOptions;
    }

    public function typeGetSubOptions()
    {
        $subOptions = [];
        $types = new \LKDev\HetznerCloud\Models\Servers\Types\ServerTypes();
        foreach ($types->all() as $key => $value){
            $subOptions[] = [
                Enum::OPTION_SUB_NAME => $value->name.'|'.$value->description,
                Enum::OPTION_SUB_OPTION_ID => $value->id,
            ];
        }
        return $subOptions;
    }

    public function userdataGetSubOptions()
    {
        $subOptions = [];
        foreach (UserData::getFilesNames() as $key => $value){
            $subOptions[] = [
                    Enum::OPTION_SUB_NAME  => "$key|$value",
                    Enum::OPTION_SUB_ORDER => Enum::OPTION_SUB_ORDER_DEFAULT,
                ];
        }
        return $subOptions;
    }

    public function getConfigurableOptions()
    {
        $configOptions = [];

        $configOptions[] = [
            Enum::OPTION_NAME         => 'location|Location',
            Enum::OPTION_TYPE         => Enum::OPTION_TYPE_DROPDOWN,
        ];
        $configOptions[] = [
            Enum::OPTION_NAME         => 'datacenter|Data Center',
            Enum::OPTION_TYPE         => Enum::OPTION_TYPE_DROPDOWN,
        ];
        $configOptions[] = [
            Enum::OPTION_NAME         => 'image|Image',
            Enum::OPTION_TYPE         => Enum::OPTION_TYPE_DROPDOWN,
        ];
        $configOptions[] = [
            Enum::OPTION_NAME         => 'type|Type',
            Enum::OPTION_TYPE         => Enum::OPTION_TYPE_DROPDOWN,
        ];

        $unit            = strtoupper($this->configuration()->getAdditionalDiskUnit());
        $max             = 1024 * 10;
        if ($this->configuration()->getAdditionalDiskUnit() == 'gb' || $this->configuration()->getAdditionalDiskUnit() == 'tb')
        {
            $max = 1000;
        }
        $configOptions[] = [
            Enum::OPTION_NAME         => 'volume|Additional Volume Size',
            Enum::OPTION_TYPE         => Enum::OPTION_TYPE_QUANTITY,
            Enum::OPTION_QUANTITY_MIN => 0,
            Enum::OPTION_QUANTITY_MAX => $max,
            Enum::CONFIG_SUB_OPTIONS  => [
                [
                    Enum::OPTION_SUB_NAME  => "{$unit}|{$unit}",
                    Enum::OPTION_SUB_ORDER => Enum::OPTION_SUB_ORDER_DEFAULT,
                ]
            ]
        ];
        $configOptions[] = [
            Enum::OPTION_NAME         => 'snapshots|Snapshots Limit',
            Enum::OPTION_TYPE         => Enum::OPTION_TYPE_QUANTITY,
            Enum::OPTION_QUANTITY_MIN => 0,
            Enum::OPTION_QUANTITY_MAX => $max,
            Enum::CONFIG_SUB_OPTIONS  => [
                [
                    Enum::OPTION_SUB_NAME  => "{$unit}|{$unit}",
                    Enum::OPTION_SUB_ORDER => Enum::OPTION_SUB_ORDER_DEFAULT,
                ]
            ]
        ];
        $configOptions[] = [
            Enum::OPTION_NAME         => 'userdata|User Data',
            Enum::OPTION_TYPE         => Enum::OPTION_TYPE_DROPDOWN,
        ];
        $configOptions[] = [
            Enum::OPTION_NAME         => 'backups|Enable Backups',
            Enum::OPTION_TYPE         => Enum::OPTION_TYPE_YES_NO,
        ];
        $configOptions[] = [
            Enum::OPTION_NAME         => 'numberOfFloatingIps|Number Of Floating IPs',
            Enum::OPTION_TYPE         => Enum::OPTION_TYPE_QUANTITY,
            Enum::OPTION_QUANTITY_MIN => 0,
            Enum::OPTION_QUANTITY_MAX => 100,
            Enum::CONFIG_SUB_OPTIONS  => [
                [
                    Enum::OPTION_SUB_NAME  => '1|1',
                    Enum::OPTION_SUB_ORDER => Enum::OPTION_SUB_ORDER_DEFAULT,
                ]
            ]
        ];
        return $configOptions;
    }
}
