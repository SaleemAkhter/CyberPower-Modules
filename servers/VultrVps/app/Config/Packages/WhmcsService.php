<?php


namespace ModulesGarden\Servers\VultrVps\App\Config\Packages;

use ModulesGarden\Servers\VultrVps\App\Api\ApiClient;
use ModulesGarden\Servers\VultrVps\App\Api\ApiClientFactory;
use ModulesGarden\Servers\VultrVps\App\Enum\ConfigurableOption;
use ModulesGarden\Servers\VultrVps\App\Enum\CustomField;
use ModulesGarden\Servers\VultrVps\App\Models\Whmcs\Product;
use ModulesGarden\Servers\VultrVps\Core\App\Packages\AppPackageConfiguration;
use ModulesGarden\Servers\VultrVps\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\VultrVps\Packages\WhmcsService\Config\Enum;
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;

class WhmcsService extends AppPackageConfiguration
{
    use RequestObjectHandler;

    const APP_CONFIGURATION =
        [
            self::PACKAGE_STATUS => self::PACKAGE_STATUS_ACTIVE,

            Enum::CUSTOM_FIELDS => [
                [
                    Enum::FIELD_NAME       =>  CustomField::INSTANCE_ID.'|Instance ID',
                    Enum::FIELD_TYPE       => Enum::FIELD_TYPE_TEXT_BOX,
                    Enum::FIELD_ADMIN_ONLY => Enum::FIELD_ADMIN_ONLY_ON
                ],
                [
                    Enum::FIELD_NAME       => CustomField::FIREWALL_GROUP_ID.'|Firewall Group ID',
                    Enum::FIELD_TYPE       => Enum::FIELD_TYPE_TEXT_BOX,
                    Enum::FIELD_ADMIN_ONLY => Enum::FIELD_ADMIN_ONLY_ON
                ],
                [
                    Enum::FIELD_NAME        => CustomField::LABEL.'|Label',
                    Enum::FIELD_TYPE        => Enum::FIELD_TYPE_TEXT_BOX,
                    Enum::FIELD_ADMIN_ONLY => Enum::FIELD_ADMIN_ONLY_ON,
                ]
            ],
            Enum::CONFIGURABLE_OPTIONS => [
                [
                    Enum::OPTION_NAME => ConfigurableOption::REGION.'|Region',
                    Enum::OPTION_TYPE => Enum::OPTION_TYPE_DROPDOWN,
                ],
                [
                    Enum::OPTION_NAME => ConfigurableOption::PLAN.'|Plan',
                    Enum::OPTION_TYPE => Enum::OPTION_TYPE_DROPDOWN,
                ],
                [
                    Enum::OPTION_NAME => ConfigurableOption::OS_ID.'|OS',
                    Enum::OPTION_TYPE => Enum::OPTION_TYPE_DROPDOWN,
                ],
                [
                    Enum::OPTION_NAME => ConfigurableOption::ISO_ID.'|ISO',
                    Enum::OPTION_TYPE => Enum::OPTION_TYPE_DROPDOWN,
                ],
                [
                    Enum::OPTION_NAME => ConfigurableOption::SNAPSHOT.'|Snapshot',
                    Enum::OPTION_TYPE => Enum::OPTION_TYPE_DROPDOWN,
                ],
                /*
                [
                    Enum::OPTION_NAME => ConfigurableOption::APPLICATION.'|Application',
                    Enum::OPTION_TYPE => Enum::OPTION_TYPE_DROPDOWN,
                ],
                */
                [
                    Enum::OPTION_NAME => ConfigurableOption::IPV6.'|IPv6',
                    Enum::OPTION_TYPE => Enum::OPTION_TYPE_YES_NO,
                ],
                [
                    Enum::OPTION_NAME => ConfigurableOption::BACKUPS.'|Backups',
                    Enum::OPTION_TYPE => Enum::OPTION_TYPE_YES_NO,
                ]
            ]
        ];
    /**
     * @var ApiClient
     */
    protected $apiClient;

    private function initApi()
    {
        if ($this->apiClient)
        {
            return;
        }
        $this->product     = new Product();
        $this->product->id = $this->getRequestValue('id');
        sl("whmcsParams")->setParams($this->product->getParams());
        $this->apiClient = (new ApiClientFactory())->fromWhmcsParams();
        $this->apiClient->account();
    }

    public function regionGetSubOptions()
    {
        $this->initApi();
        $subOptions = [];
        foreach ($this->apiClient->regions() as $entery)
        {
            $subOptions[] = [
                Enum::OPTION_SUB_NAME   =>  sprintf("%s|%s - %s", $entery->getId() ,$entery->getCity() ,$entery->getCountry()),
                Enum::OPTION_SUB_ORDER  => Enum::OPTION_SUB_ORDER_DEFAULT,
                Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
            ];
        }
        return $subOptions;
    }

    public function planGetSubOptions()
    {
        $this->initApi();
        $subOptions = [];
        foreach ($this->apiClient->plans() as $entery)
        {
            $subOptions[] = [
                Enum::OPTION_SUB_NAME   =>  sprintf("%s|%s", $entery->id ,$entery->id),
                Enum::OPTION_SUB_ORDER  => Enum::OPTION_SUB_ORDER_DEFAULT,
                Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
            ];
        }
        return $subOptions;
    }

    public function os_idGetSubOptions()
    {
        $this->initApi();
        $subOptions = [];
        $subOptions[] = [
            Enum::OPTION_SUB_NAME   =>  '0|Disabled',
            Enum::OPTION_SUB_ORDER  => Enum::OPTION_SUB_ORDER_DEFAULT,
            Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
        ];
        $osRepository = $this->apiClient->os();
        $osRepository->findNotName(['Snapshot','Custom','Backup','Application']);
        foreach ( $osRepository->get() as $entery)
        {
            $subOptions[] = [
                Enum::OPTION_SUB_NAME   => $entery->id . '|' . $entery->name,
                Enum::OPTION_SUB_ORDER  => Enum::OPTION_SUB_ORDER_DEFAULT,
                Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
            ];
        }
        return $subOptions;
    }

    public function iso_idGetSubOptions()
    {
        $this->initApi();
        $subOptions = [];
        $subOptions[] = [
            Enum::OPTION_SUB_NAME   =>  '0|Disabled',
            Enum::OPTION_SUB_ORDER  => Enum::OPTION_SUB_ORDER_DEFAULT,
            Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
        ];
        foreach ($this->apiClient->iso() as $entery)
        {
            $subOptions[] = [
                Enum::OPTION_SUB_NAME   =>  sprintf("%s|%s", $entery->id, $entery->filename),
                Enum::OPTION_SUB_ORDER  => Enum::OPTION_SUB_ORDER_DEFAULT,
                Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
            ];
        }
        return $subOptions;
    }

    public function snapshot_idGetSubOptions()
    {
        $this->initApi();
        $subOptions = [];
        $subOptions[] = [
            Enum::OPTION_SUB_NAME   =>  '0|Disabled',
            Enum::OPTION_SUB_ORDER  => Enum::OPTION_SUB_ORDER_DEFAULT,
            Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
        ];
        foreach ($this->apiClient->snapshots() as $entery)
        {
            $subOptions[] = [
                Enum::OPTION_SUB_NAME   =>  sprintf("%s|%s", $entery->id, $entery->description),
                Enum::OPTION_SUB_ORDER  => Enum::OPTION_SUB_ORDER_DEFAULT,
                Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
            ];
        }
        return $subOptions;
    }

    public function ipv6GetSubOptions()
    {
        $this->initApi();
        $subOptions = [];
        $subOptions[] = [
            Enum::OPTION_SUB_NAME   =>  'Enable IPv6 address assigned to the instance',
            Enum::OPTION_SUB_ORDER  => Enum::OPTION_SUB_ORDER_DEFAULT,
            Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
        ];
        return $subOptions;
    }

    public function backupsGetSubOptions()
    {
        $this->initApi();
        $subOptions = [];
        $subOptions[] = [
            Enum::OPTION_SUB_NAME   =>  'Enable Auto Backups',
            Enum::OPTION_SUB_ORDER  => Enum::OPTION_SUB_ORDER_DEFAULT,
            Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
        ];
        return $subOptions;
    }

    public function app_idGetSubOptions()
    {
        $this->initApi();
        $subOptions = [];
        $subOptions[] = [
            Enum::OPTION_SUB_NAME   =>  '0|Disabled',
            Enum::OPTION_SUB_ORDER  => Enum::OPTION_SUB_ORDER_DEFAULT,
            Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
        ];
        foreach ($this->apiClient->applications() as $entery)
        {
            $subOptions[] = [
                Enum::OPTION_SUB_NAME   =>  sprintf("%s|%s", $entery->id, $entery->deploy_name),
                Enum::OPTION_SUB_ORDER  => Enum::OPTION_SUB_ORDER_DEFAULT,
                Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
            ];
        }
        return $subOptions;
    }

}
