<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Config\Packages;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ApiClient;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\GoogleClientFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Models\Whmcs\Product;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories\ProductSettingRepository;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ComputeTrait;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Packages\AppPackageConfiguration;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\FileReader\Reader\Json;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\ModuleConstants;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Packages\WhmcsService\Config\Enum;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;

class WhmcsService extends AppPackageConfiguration
{
    use RequestObjectHandler;
    use ComputeTrait;

    /**
     * @var ApiClient
     */
    protected $apiClient;

    const APP_CONFIGURATION =
    [
        self::PACKAGE_STATUS => self::PACKAGE_STATUS_ACTIVE,

        Enum::CUSTOM_FIELDS => [
            [
                Enum::FIELD_NAME => 'instanceId|Instance ID',
                Enum::FIELD_TYPE => Enum::FIELD_TYPE_TEXT_BOX,
                Enum::FIELD_ADMIN_ONLY => Enum::FIELD_ADMIN_ONLY_ON
            ],
            [
                Enum::FIELD_NAME => 'region|Region',
                Enum::FIELD_TYPE => Enum::FIELD_TYPE_TEXT_BOX,
                Enum::FIELD_ADMIN_ONLY => Enum::FIELD_ADMIN_ONLY_ON
            ],
            [
                Enum::FIELD_NAME => 'zone|Zone',
                Enum::FIELD_TYPE => Enum::FIELD_TYPE_TEXT_BOX,
                Enum::FIELD_ADMIN_ONLY => Enum::FIELD_ADMIN_ONLY_ON
            ],
            [
                Enum::FIELD_NAME        => 'sshKey|SSH Public Key',
                Enum::FIELD_TYPE        => Enum::FIELD_TYPE_TEXTAREA,
                Enum::FIELD_REG_EXPR    => '#ssh-rsa AAAA[0-9A-Za-z+/]+[=]{0,3}( [^@]+@[^@]+)?#',
                Enum::FIELD_REQUIRED    => Enum::FIELD_REQUIRED_ON,
                Enum::FIELD_SHOW_ORDER  => Enum::FIELD_SHOW_ORDER_ON,
                Enum::FIELD_DESCRIPTION => 'Enter your public key in the OpenSSH format here (e.g. ssh-rsa).'
            ],

        ],

        Enum::CONFIGURABLE_OPTIONS => [
            [
                Enum::OPTION_NAME => 'machineType|Machine Type',
                Enum::OPTION_TYPE => Enum::OPTION_TYPE_DROPDOWN,
            ],
            [
                Enum::OPTION_NAME => 'image|Image',
                Enum::OPTION_TYPE => Enum::OPTION_TYPE_DROPDOWN,
            ],
            [
                Enum::OPTION_NAME => 'customMachineType|Custom Machine Series',
                Enum::OPTION_TYPE => Enum::OPTION_TYPE_DROPDOWN,
            ],
            [
                Enum::OPTION_NAME => 'customMachineCpu|Custom Machine Cores',
                Enum::OPTION_DESCRIPTION => 'customMachineCpu|Cores',
                Enum::OPTION_TYPE => Enum::OPTION_TYPE_QUANTITY,
            ],
            [
                Enum::OPTION_NAME => 'customMachineRam|Custom Machine Memory [MB]',
                Enum::OPTION_DESCRIPTION => 'customMachineRam|MB',
                Enum::OPTION_TYPE => Enum::OPTION_TYPE_QUANTITY,
            ],
        ]
    ];

    public function imageGetSubOptions()
    {
        $subOptions=[];
        $dataJson    = new Json('image_projects.json', ModuleConstants::getFullPath('storage', 'app'));
        $project = (new ProjectFactory())->fromParams();

        foreach ($dataJson->get() as $imageProject => $imageProjectName) {
            if ($imageProject === 'custom-images') {
                foreach ($this->compute()->images->listImages($project)->getItems() as $entry) {
                    $subOptions[] = [
                        Enum::OPTION_SUB_NAME => sprintf("%s:%s|%s", $project, $entry->getName(), $entry->getDescription() ?? $entry->getName()),
                        Enum::OPTION_SUB_ORDER => Enum::OPTION_SUB_ORDER_DEFAULT,
                        Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
                    ];
                }
            } else {
                foreach ($this->compute()->images->listImages($imageProject)->getItems() as $entry) {
                    if ($entry->deprecated) {
                        continue;
                    }
                    $subOptions[] = [
                        Enum::OPTION_SUB_NAME => sprintf("%s:%s|%s", $imageProject, $entry->getName(), $entry->getDescription()),
                        Enum::OPTION_SUB_ORDER => Enum::OPTION_SUB_ORDER_DEFAULT,
                        Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
                    ];
                }

            }
        }

        return $subOptions;
    }

    public function machineTypeGetSubOptions()
    {
        $subOptions=[];
        $options=[];
        $options['customMachine'] = 'Custom Machine';
        $productSetting = new ProductSettingRepository($this->getRequestValue('id'));
        $z = $productSetting->get('zone', 'us-central1-c');
        $project = sl('ApiClient')->getProject();
        foreach ($this->compute()->machineTypes->listMachineTypes($project,$z)->getItems() as $entery)
        {
            /**
             * @var  \Google_Service_Compute_MachineType $entery
             */
            $options[$entery->getName()] =  sprintf("%s %s ",$entery->getName(), $entery->getDescription())  ;

        }
        foreach ($options as $key => $value){
            $subOptions[] = [
                Enum::OPTION_SUB_NAME =>  $key. '|' . $value,
                Enum::OPTION_SUB_ORDER => Enum::OPTION_SUB_ORDER_DEFAULT,
                Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
            ];
        }
        unset($options);
        return $subOptions;
    }

    public function customMachineTypeGetSubOptions()
    {
        $options = ['N1', 'N2', 'N2D', 'E2'];

        foreach ($options as $option){
            $subOptions[] = [
                Enum::OPTION_SUB_NAME =>  $option,
                Enum::OPTION_SUB_ORDER => Enum::OPTION_SUB_ORDER_DEFAULT,
                Enum::OPTION_SUB_HIDDEN => Enum::OPTION_SUB_HIDDEN_DEFAULT
            ];
        }
        unset($options);
        return $subOptions;
    }
}
