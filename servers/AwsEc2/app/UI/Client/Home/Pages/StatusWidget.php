<?php

/**********************************************************************
* AwsEc2 product developed. (2019-04-26)
*
*
*  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
*  CONTACT                        ->       contact@modulesgarden.com
*
*
* This software is furnished under a license and may be used and copied
* only  in  accordance  with  the  terms  of such  license and with the
* inclusion of the above copyright notice.  This software  or any other
* copies thereof may not be provided or otherwise made available to any
* other person.  No title to and  ownership of the  software is  hereby
* transferred.
*
*
**********************************************************************/

/**
* @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
*/

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Pages;

use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\App\Models\SSHKey\SSHKeysRepository;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons\InjectSshKey;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons\Reboot;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons\Start;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons\Stop;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons\WindowsPassword;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons\GetKey;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons\ElasticIp;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons\PrivateIp;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons\NetworkManager;

use ModulesGarden\Servers\AwsEc2\Core\Traits\IsAdmin;
use ModulesGarden\Servers\AwsEc2\Core\Traits\Lang;
use ModulesGarden\Servers\AwsEc2\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\Servers\AwsEc2\Core\Helper\BuildUrl;

class StatusWidget extends BaseContainer implements ClientArea, AdminArea, AjaxElementInterface
{
    use IsAdmin;
    use Lang;

    const STATUS = 'status';
    const STATUS_COLOR = 'statusColor';
    const STATUS_COLOR_SUCCESS = 'success';
    const STATUS_COLOR_DEFAULT = 'default';
    const STATUS_COLOR_WARNING = 'warning';
    const STATUS_COLOR_DANGER = 'danger';

    const API_ERROR = 'apiError';

    const BUTTONS_STATES = 'buttonsStates';
    const BUTTON_ID = 'buttonId';
    const BUTTON_STATE = 'buttonState';
    const BUTTON_STATE_ACTIVE = 'active';
    const BUTTON_STATE_HIDDEN = 'hidden';
    const BUTTON_STATE_DISABLED = 'disabled';

    const INSTANCE_DETAILS = 'instanceDetails';

    protected $id = 'statusWidget';
    protected $name = 'statusWidget';
    protected $title = 'statusWidgetTitle';

    protected $isDnsHidden = true;
    protected $isIpv6Hidden = true;

    protected $vueComponent = true;
    protected $defaultVueComponentName = 'mg-service-actions';

    public function initContent()
    {
        $this->customTplVars['isAdmin'] = $this->isAdmin();

        $this->addButton(Start::class);
        $this->addButton(Stop::class);
        $this->addButton(Reboot::class);
        if($this->wasSshKeyGenerated())
            $this->addButton(GetKey::class);
        if(!$this->isAdmin())
        $this->addButton(InjectSshKey::class);

        $customFields = $this->getWhmcsParamByKey('customfields');
        $password = $customFields['windowsPassword'];
        if (!$this->isAdmin() && trim($password) !== '')
        {
            $this->addButton(WindowsPassword::class);
        }
    }

    /**
     * Should always return an array for the ajax call, 'status' param is required
     */
    public function prepareAjaxData()
    {
        try
        {
            $productId = $this->getWhmcsParamByKey('packageid');
            $serviceId = $this->getWhmcsParamByKey('serviceid');

            $awsClient = new ClientWrapper($productId, $serviceId);

            $this->isDnsHidden = $awsClient->getProductConfig($productId)['hideDnsName'] === 'on' ? true : false;
            $this->isIpv6Hidden = $awsClient->getProductConfig($productId)['hideIpv6'] === 'on' ? true : false;

            $customFields = $this->getWhmcsParamByKey('customfields');

            $instancesData = $awsClient->describeInstances(['InstanceIds' => [$customFields['InstanceId']]]);
            $reservations = $instancesData->get('Reservations');

            $instanceData = $reservations[0]['Instances'][0];

            $status = $instanceData["State"]['Name'];
        }
        catch (\Aws\Exception\AwsException $exc)
        {
            return [
                self::API_ERROR => $exc->getAwsErrorMessage(),
                self::BUTTONS_STATES => $this->getButtonStates('unknown'),
                self::STATUS => 'unknown',
                self::STATUS_COLOR => self::STATUS_COLOR_DANGER,
                self::INSTANCE_DETAILS => []
            ];
        }

        return [
            self::STATUS => $status,
            self::BUTTONS_STATES => $this->getButtonStates($status),
            self::STATUS_COLOR => $this->getStatusColor($status),
            self::INSTANCE_DETAILS => $this->parseInstanceDetails($instanceData)
        ];
    }

    public function getStatusColor($status)
    {
        if ($status === 'running')
        {
            return self::STATUS_COLOR_SUCCESS;
        }

        if ($status === 'pending')
        {
            return self::STATUS_COLOR_WARNING;
        }

        if ($status === 'stopped')
        {
            return self::STATUS_COLOR_DEFAULT;
        }

        return self::STATUS_COLOR_DANGER;
    }

    public function getButtonStates($componentStatus = null)
    {
        $states = [];
        if (in_array($componentStatus, [null, 'unknown', 'pending', 'stopping']))
        {
            foreach ($this->getButtons() as $button)
            {
                $states[$button->getId()] = self::BUTTON_STATE_DISABLED;
            }

            return $states;
        }

        foreach ($this->getButtons() as $button)
        {
            if ($button->getId() === 'start' && $componentStatus === 'running')
            {
                $states[$button->getId()] = self::BUTTON_STATE_DISABLED;

                continue;
            }

            if (($button->getId() === 'stop' || $button->getId() === 'reboot') && $componentStatus === 'stopped')
            {
                $states[$button->getId()] = self::BUTTON_STATE_DISABLED;

                continue;
            }

            if(($button->getId() === 'injectSshKey') && ($componentStatus !== 'stopped' || $this->isAdmin()))
            {
                $states[$button->getId()] = self::BUTTON_STATE_DISABLED;

                continue;
            }

            $states[$button->getId()] = self::BUTTON_STATE_ACTIVE;
        }

        return $states;
    }

    public function returnAjaxData()
    {
        return (new RawDataJsonResponse($this->prepareAjaxData()))->setCallBackFunction($this->callBackFunction)->setRefreshTargetIds($this->refreshActionIds);
    }

    public function getInstanceStatus()
    {
        $statuses = $this->prepareAjaxData();

        if ($statuses[self::STATUS] && trim($statuses[self::STATUS]) !== '')
        {
            return $statuses[self::STATUS];
        }

        return 'unknown';
    }

    public function parseInstanceDetails($instanceData = [])
    {
        if (!is_array($instanceData))
        {
            return [];
        }

        $this->loadLang();
        $details = [];
        $dnsInfo = [];
        $isAdmin = $this->isAdmin();

        if(!$this->isDnsHidden)
        {
            $dnsInfo[] = [
                'key' => $this->lang->translate('statusTitle', 'PublicDnsName'),
                'value' => $this->fillEmptyValue((string)$instanceData['PublicDnsName'])
            ];
        }

        //InstanceDetails
        $details['InstanceDetails'] = [
            'title' => $this->lang->translate('statusWidget', 'InstanceDetailsTitle'),
            'details' => [
                [
                    'key' => $this->lang->translate('statusTitle', 'InstanceStatus'),
                    'value' => ucfirst($instanceData["State"]['Name']),
                    'isStatus' => true
                ],
                [
                    'key' => $this->lang->translate('statusTitle', 'CoreCount'),
                    'value' => $this->fillEmptyValue((string)$instanceData['CpuOptions']['CoreCount'])
                ],
                [
                    'key' => $this->lang->translate('statusTitle', 'ThreadsPerCore'),
                    'value' => $this->fillEmptyValue((string)$instanceData['CpuOptions']['ThreadsPerCore'])
                ],

            ]

        ];
        $details['InstanceDetails']['details'] = array_merge($details['InstanceDetails']['details'], $dnsInfo);

        //InstanceDetails admin
        if ($isAdmin)
        {
            $adminDetails = [
                [
                    'key' => $this->lang->translate('statusTitle', 'InstanceStatus'),
                    'value' => ucfirst($instanceData["State"]['Name']),
                    'isStatus' => true
                ],
                [
                    'key' => $this->lang->translate('statusTitle', 'ImageId'),
                    'value' => $this->fillEmptyValue($instanceData['ImageId'])
                ],
                [
                    'key' => $this->lang->translate('statusTitle', 'InstanceId'),
                    'value' => $this->fillEmptyValue($instanceData['InstanceId'])
                ],
                [
                    'key' => $this->lang->translate('statusTitle', 'InstanceType'),
                    'value' => $this->fillEmptyValue($instanceData['InstanceType'])
                ],
                [
                    'key' => $this->lang->translate('statusTitle', 'AvailabilityZone'),
                    'value' => $this->fillEmptyValue($instanceData['Placement']['AvailabilityZone'])
                ],
                [
                    'key' => $this->lang->translate('statusTitle', 'VirtualizationType'),
                    'value' => $this->fillEmptyValue($instanceData['VirtualizationType'])
                ]
            ];

            unset($details['InstanceDetails']['details'][0]);
            $details['InstanceDetails']['details'] = array_merge($adminDetails, $details['InstanceDetails']['details']);

            $details['InstanceDetails']['details'][] = [
                'key' => $this->lang->translate('statusTitle', 'tags'),
                'value' => $this->fillEmptyValue($this->parseTagsData($instanceData['Tags']))
            ];
        }

        //network interfaces
        foreach ($instanceData['NetworkInterfaces'] as $id => $interface)
        {
            $idToDisplay = ((int)$id) + 1;

            array_merge();
            $details['NetworkInterface' . $idToDisplay] = [
                'title' => $this->lang->translate('statusWidget', 'NetworkInterface') . ' ' . $idToDisplay,
                'details' => [
                    [
                        'key' => $this->lang->translate('statusTitle', 'PublicIp'),
                        'value' => $this->fillEmptyValue($interface['Association']['PublicIp'])
                    ],
                    [
                        'key' => $this->lang->translate('statusTitle', 'MacAddress'),
                        'value' => $this->fillEmptyValue($interface['MacAddress'])
                    ]
                ]
            ];

            if($isAdmin) {
                array_unshift($details['NetworkInterface' . $idToDisplay]['details'], [
                    'key' => $this->lang->translate('statusTitle', 'NetworkInterfaceId'),
                    'value' => $this->fillEmptyValue($interface['NetworkInterfaceId'])
                ]);
            }


            //ipv6
            if(!$this->isIpv6Hidden || $isAdmin) {
                $ipv6Addresses = [];
                foreach ($interface['Ipv6Addresses'] as $ipv6) {
                    $ipv6Addresses[] = $ipv6['Ipv6Address'];
                }
                $details['NetworkInterface' . $idToDisplay]['details'][] = [
                    'key' => $this->lang->translate('statusTitle', 'Ipv6Addresses'),
                    'value' => $this->fillEmptyValue(implode(', ', $ipv6Addresses))
                ];
            }

            //private ips only for admin
            if ($isAdmin)
            {
                 $priveteAddresses = [];
                 foreach ($interface['PrivateIpAddresses'] as $privateIp)
                 {
                     $priveteAddresses[] = $privateIp['PrivateIpAddress'];
                 }
                $details['NetworkInterface' . $idToDisplay]['details'][] = [
                    'key' => $this->lang->translate('statusTitle', 'PrivateIpAddresses'),
                    'value' => $this->fillEmptyValue(implode(', ', $priveteAddresses))
                ];
                $details['NetworkInterface' . $idToDisplay]['details'][] = [
                    'key' => $this->lang->translate('statusTitle', 'SecurityGroupId'),
                    'value' => $this->fillEmptyValue(
                        implode(", ",(
                            array_map(function ($securityGroup) {return $securityGroup['GroupId'];},
                            $interface['Groups'])
                        )))
                ];
            }
        }

        return $details;
    }

    public function parseTagsData($tags = [])
    {
        if (!is_array($tags) || count($tags) === 0)
        {
            return '-';
        }

        $tagLines = [];
        foreach ($tags as $tag)
        {
            $tagLines[] = $tag['Key'] . ':' . $tag['Value'];
        }

        return implode(', ', $tagLines);
    }

    public function fillEmptyValue($data = null)
    {
        if (!$data)
        {
            return '-';
        }

        if (is_string($data) && trim($data) === '')
        {
            return '';
        }

        return $data;
    }

    private function wasSshKeyGenerated()
    {
        $sshRepo = new SSHKeysRepository();
        $serviceId = $this->getWhmcsParamByKey('serviceid');

        return $sshRepo->get($serviceId)['public_key'] !== null;
    }
}
