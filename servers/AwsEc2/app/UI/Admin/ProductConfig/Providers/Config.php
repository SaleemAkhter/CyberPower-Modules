<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Providers;

use ModulesGarden\Servers\AwsEc2\App\Helpers\UserData;
use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\App\Models\AvailableImages\Model;
use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\Core\Models\Whmcs\EmailTemplate;
use ModulesGarden\Servers\AwsEc2\Core\ModuleConstants;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\AwsEc2\App\Helpers\VolumeType;

class Config extends BaseDataProvider implements AdminArea
{
    private $awsClient = null;
    protected $productId = null;
    private $productSettings = [];


    public function read()
    {
        $this->loadProductConfig();
        $this->loadConfig();
    }

    public function update()
    {
        $this->read();
        $formData = $this->getRequestValue('mgpci');
        $checkboxes = ['hibernationOptions', 'capacityReservationSpecification', 'logApiRequests', 'hideDnsName', 'hideScheduledTasks', 'hideIpv6', 'firewall', 'enableFirewallConfig', 'getWindowsPassword'];

        foreach ($checkboxes as $checkbox)
        {
            if (!$formData[$checkbox])
            {
                $formData[$checkbox] = 'off';
            }
        }

        if (!isset($formData['securityGroups']))
        {
            $formData['securityGroups'] = [];
        }

        foreach ($formData as $name => $value)
        {
            if (isset($this->availableValues[$name]))
            {
                if (is_array($value))
                {
                    $corectValues = [];
                    foreach ($value as $option)
                    {
                        if (isset($this->availableValues[$name][$option]))
                        {
                            $corectValues[] = $option;
                        }
                    }
                    $value = json_encode($corectValues);
                }
                else if (!isset($this->availableValues[$name][$value]))
                {
                    continue;
                }
            }

            $settingRepo = new Repository();
            $settingRepo->updateProductSetting($this->productId, $name, $value);
        }
    }

    private function loadConfig()
    {
        //InstanceType
        $this->data['instanceType'] = $this->productSettings['instanceType'] ? : null;
        $this->availableValues['instanceType'] = $this->getInstanceTypes();

        //Capacity Reservation Specification
        $this->data['capacityReservationSpecification'] =  $this->productSettings['capacityReservationSpecification'] ? : 'off';
        $this->availableValues['capacityReservationSpecification'] = ['on' => 'on', 'off' => 'off'];

        //Hibernation Options
        $this->data['hibernationOptions'] =  $this->productSettings['hibernationOptions'] ? : 'off';
        $this->availableValues['hibernationOptions'] = ['on' => 'on', 'off' => 'off'];

        $this->data['tagName'] = $this->productSettings['tagName'] ? : '';

        $this->data['userData'] = $this->productSettings['userData'] ? : '';

        $this->data['userDataFile'] = $this->productSettings['userDataFile'] ? : '';
        $this->availableValues['userDataFile'] = $this->getUserDataFileNames();

        $this->data['firewall'] = $this->productSettings['firewall'] ? : '';
        $this->availableValues['firewall'] = ['on' => 'on', 'off' => 'off'];

        $this->data['securityGroups'] = $this->productSettings['securityGroups'] ? $this->productSettings['securityGroups'] : null;
        $this->availableValues['securityGroups'] = $this->loadAvailableSecurityGroups();

        $this->data['logApiRequests'] =  $this->productSettings['logApiRequests'] ? : 'off';
        $this->availableValues['logApiRequests'] = ['on' => 'on', 'off' => 'off'];

        $this->data['getWindowsPassword'] =  $this->productSettings['getWindowsPassword'] ? : 'off';
        $this->availableValues['getWindowsPassword'] = ['on' => 'on', 'off' => 'off'];

        $this->data['hideDnsName'] =  $this->productSettings['hideDnsName'] ? : 'off';
        $this->availableValues['hideDnsName'] = ['on' => 'on', 'off' => 'off'];

        $this->data['hideScheduledTasks'] =  $this->productSettings['hideScheduledTasks'] ? : 'off';
        $this->availableValues['hideScheduledTasks'] = ['on' => 'on', 'off' => 'off'];

        $this->data['hideIpv6'] =  $this->productSettings['hideIpv6'] ? : 'off';
        $this->availableValues['hideIpv6'] = ['on' => 'on', 'off' => 'off'];

        $this->data['enableFirewallConfig'] =  $this->productSettings['enableFirewallConfig'] ? : 'off';
        $this->availableValues['enableFirewallConfig'] = ['on' => 'on', 'off' => 'off'];

        $this->data['ipv4'] = $this->productSettings['ipv4'] ? : '0';
        $this->data['ipv6'] = $this->productSettings['ipv6'] ? : '0';

        $this->availableValues['volumeType'] = VolumeType::getVolumeTypes();
        $this->data['volumeSize'] = $this->productSettings['volumeSize'];
        $this->data['iops'] = $this->productSettings['iops'];

        $this->data['numberOfFirewallRules'] = $this->productSettings['numberOfFirewallRules'] ? : null;

        $this->data['emailTemplate'] = $this->productSettings['emailTemplate'] ? : null;
        $this->availableValues['emailTemplate'] = $this->getEmailTemplates();
    }

    protected function loadAvailableSecurityGroups()
    {
        $groups = $this->awsClient->getSecurityGroups([]);
        $availableGroups = [];
        foreach ($groups as $group)
        {
            $availableGroups[$group['GroupId']] = $group['GroupName'];
        }

        asort($availableGroups);

        return $availableGroups;
    }

    protected function loadProductConfig()
    {
        $this->productId = $this->getRequestValue('id');

        $settingRepo = new Repository();
        $this->productSettings = $settingRepo->getProductSettings($this->productId);

        $this->awsClient = new ClientWrapper($this->productId);
    }

    public function getInstanceTypes()
    {
        $filePath = ModuleConstants::getModuleRootDir() . DS . 'storage' . DS . 'app' . DS . 'instanceTypes.json';

        $dataContent = file_get_contents($filePath);

        $types = [];
        foreach(json_decode($dataContent)->families as $family)
        {
            foreach ($family->types as $type)
            {
                $types[$type->typeName] = $type->typeName;
            }
        }

        return $types;
    }

    public function getUserDataFileNames()
    {
        return UserData::getFilesNames();
    }

    public function getEmailTemplates()
    {
        $records = EmailTemplate::select('id', 'name')->where('type', 'product')->pluck('name', 'id')->all();
        return ['off' => ''] + $records;
    }
}
