<?php

/**********************************************************************
* GoogleCloudVirtualMachines product developed. (2019-04-26)
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

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Pages;

use GuzzleHttp\Psr7\Request;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\GoogleServiceComputeFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\InstanceFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\Models\Instance;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Enum\CustomField;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Buttons\Reset;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Buttons\ResetPassword;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Buttons\Start;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Buttons\Stop;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Buttons\GetRdpFile;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\BuildUrl;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Traits\IsAdmin;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Traits\Lang;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Forms;

class StatusWidget extends BaseContainer implements ClientArea, AdminArea, AjaxElementInterface
{
    use IsAdmin;
    use Lang;
    /**
     * @var Instance
     */
    protected $instance;

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

    protected $vueComponent = true;
    protected $defaultVueComponentName = 'mg-service-actions';

    /**
     * @var \Google_Service_Compute
     */
    private $compute;

    public function initContent()
    {
        $this->customTplVars['isAdmin'] = $this->isAdmin();
        $this->addButton(Start::class);
        $this->addButton(Stop::class);
        $this->addButton(Reset::class);

        if ($this->checkDisplayDownloadRdpButton() && !$this->isAdmin()) {
            $downloadRdpFie = new GetRdpFile();
            $downloadRdpFie->setRawUrl(BuildUrl::getUrl('home', 'getRdpFile', [], true, true) . '&ajax=1&action=productdetails')
                ->setRedirectParams([
                    'namespace' => str_replace('\\', '_', Forms\GetRdpFile::class),
                    'id' => $this->request->query->get('id')
                ]);
            $this->addButton($downloadRdpFie);

            $this->addButton(ResetPassword::class);
        }
    }

    /**
     * Should always return an array for the ajax call, 'status' param is required
     */
    public function prepareAjaxData()
    {

        try
        {
            $instace = (new InstanceFactory())->fromParams();
            $this->compute = (new GoogleServiceComputeFactory())->fromParams();
            $poject = (new ProjectFactory())->fromParams();
            $instanceData =  $this->compute->instances->get($poject , $instace->getZone(), $instace->getId() );
            $status = $instanceData->getStatus();
        }
        catch (\Exception $exc)
        {
            return [
                self::API_ERROR => $exc->getMessage(),
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
        if ($status === 'RUNNING')
        {
            return self::STATUS_COLOR_SUCCESS;
        }

        if ($status === 'starting')
        {
            return self::STATUS_COLOR_WARNING;
        }

        if ($status === 'STOPPING')
        {
            return self::STATUS_COLOR_DEFAULT;
        }

        return self::STATUS_COLOR_DANGER;
    }

    public function getButtonStates($componentStatus = null)
    {
        $states = [];
        switch ($componentStatus){
            case 'RUNNING':
                $states['start'] = self::BUTTON_STATE_DISABLED;
                break;
            case 'STOPPING':
            case 'TERMINATED':
            foreach ($this->getButtons() as $button)
            {
                if ($button->getId() === 'start')
                {
                    continue;
                }
                $states[$button->getId()] = self::BUTTON_STATE_DISABLED;
            }
                break;
            default: // status in pendiung disable all buttons
                foreach ($this->getButtons() as $button)
                {
                    $states[$button->getId()] = self::BUTTON_STATE_DISABLED;
                }
        }
        return $states;
    }

    public function returnAjaxData()
    {
        return (new RawDataJsonResponse($this->prepareAjaxData()))->setCallBackFunction($this->callBackFunction)->setRefreshTargetIds((array)$this->refreshActionIds);
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

    /**
     * @param \Google_Service_Compute_Instance $instanceData
     * @return array
     */
    public function parseInstanceDetails($instanceData = [])
    {
        if (!is_object($instanceData))
        {
            return [];
        }

        $this->loadLang();
        $details = [];
        $isAdmin = $this->isAdmin();

        //machine type
        $request = new Request(
            "GET",
            $instanceData->getMachineType(),
            ['content-type' => 'application/json'],
            ''
        );
        /**
         * @var \Google_Service_Compute_MachineType $machineType
         */
        $machineType =  $this->compute->getClient()->execute($request,\Google_Service_Compute_MachineType::class);
        //created
        $created = new \DateTime($instanceData->getCreationTimestamp(),new \DateTimeZone('UTC'));
        $created->setTimezone( new \DateTimeZone(date_default_timezone_get ( )) );
        //InstanceDetails
        $details['InstanceDetails'] = [
            'title' => $this->lang->translate('statusWidget', 'InstanceDetailsTitle'),
            'details' => [
                [
                    'key' => $this->lang->translate('Name'),
                    'value' => $instanceData->getName(),
                ],
                [
                    'key' => $this->lang->translate('Status'),
                    'value' => $this->lang->translate($instanceData->getStatus()),
                    'isStatus' => true
                ],
                [
                    'key' => $this->lang->translate('Machine Type'),
                    'value' =>  sprintf("%s %s", $machineType->name, $machineType->getDescription()),
                ],
                [
                    'key' => $this->lang->translate('Created'),
                    'value' =>  $created->format("Y-m-d H:i:s"),
                ],
                [
                    'key' => $this->lang->translate('Zone'),
                    'value' =>  $this->lang->abtr('zone',$this->getWhmcsCustomField(CustomField::ZONE)),
                ],
                [
                    'key' => $this->lang->translate('CPU Platform'),
                    'value' =>  $instanceData->getCpuPlatform(),
                ],
            ]
        ];
        //network interfaces
        foreach ($instanceData->getNetworkInterfaces() as $interface)
        {
            /**
             * @var \Google_Service_Compute_NetworkInterface $interface
             */
            $request = new Request(
                "GET",
                $interface->getSubnetwork(),
                ['content-type' => 'application/json'],
                ''
            );
            /**
             * @var \Google_Service_Compute_Network $network
             */
            $network =  $this->compute->getClient()->execute($request,\Google_Service_Compute_Network::class);
            $details['NetworkInterface' . $interface->getName() ] = [
                'title' => $this->lang->translate('statusWidget', 'NetworkInterface') . ' ' . $interface->getName(),
                'details' => [
                    [
                        'key' => $this->lang->translate('Name'),
                        'value' => $interface->getName()
                    ],
                    [
                        'key' => $this->lang->translate('Network'),
                        'value' => $network->getDescription() ? $network->getDescription() : $network->getName()
                    ],
                    [
                        'key' => $this->lang->translate('Private IP'),
                        'value' => $interface->getNetworkIP()
                    ],
                    [
                        'key' => $this->lang->translate('Public IP'),
                        'value' => $interface->accessConfigs[0]->natIP ? $interface->accessConfigs[0]->natIP : '-'
                    ],

                ]
            ];
        }
        //Disks
        foreach ($instanceData->getDisks() as $attachedDisk)
        {
            /**
             * @var \Google_Service_Compute_AttachedDisk $attachedDisk
             */
            $request = new Request(
                "GET",
                $attachedDisk->getSource(),
                ['content-type' => 'application/json'],
                ''
            );
            /**
             * @var \Google_Service_Compute_Disk $disk
             */
            $disk =  $this->compute->getClient()->execute($request,\Google_Service_Compute_Disk::class);
            $image = null;
            if($disk->getSourceImage())
            {
                $request = new Request(
                    "GET",
                    $disk->getSourceImage(),
                    ['content-type' => 'application/json'],
                    ''
                );
                /**
                 * @var \Google_Service_Compute_Image $image
                 */
                $image = $this->compute->getClient()->execute($request,\Google_Service_Compute_Image::class);
            }

            $details['Disk' . $attachedDisk->getDeviceName() ] = [
                'title' => $this->lang->translate('statusWidget', 'Disk') . ' ' . $attachedDisk->getDeviceName(),
                'details' => [
                    [
                        'key' => $this->lang->translate('Device Name'),
                        'value' => $attachedDisk->getDeviceName()
                    ],
                    [
                        'key' => $this->lang->translate('Image'),
                        'value' => is_object($image) ? $image->getDescription(): '-'
                    ],
                    [
                        'key' => $this->lang->translate('Size'),
                        'value' => $attachedDisk->diskSizeGb. " GB"
                    ],
                    [
                        'key' => $this->lang->translate('Type'),
                        'value' => $this->lang->translate('diskType', $attachedDisk->getType())
                    ],
                    [
                        'key' => $this->lang->translate('Mode'),
                        'value' => $this->lang->translate('diskMode', $attachedDisk->getMode())
                    ],
                ]
            ];
        }

        return $details;
    }

    private function checkDisplayDownloadRdpButton(): bool
    {
        $instance = (new InstanceFactory())->fromParams();
        $this->compute = (new GoogleServiceComputeFactory())->fromParams();
        $project = (new ProjectFactory())->fromParams();
        $instanceData = $this->compute->instances->get($project, $instance->getZone(), $instance->getId());

        $publicIp = $this->compute->instances->get($project, $instance->getZone(), $instance->getId())["networkInterfaces"][0]->accessConfigs[0]->natIP;

        if (empty($publicIp)) {
            return false;
        }

        foreach ($instanceData->getDisks() as $attachedDisk) {
            /**
             * @var \Google_Service_Compute_AttachedDisk $attachedDisk
             */
            $request = new Request(
                "GET",
                $attachedDisk->getSource(),
                ['content-type' => 'application/json'],
                ''
            );
            /**
             * @var \Google_Service_Compute_Disk $disk
             */
            $disk = $this->compute->getClient()->execute($request, \Google_Service_Compute_Disk::class);
            if ($disk->getSourceImage()) {
                $request = new Request(
                    "GET",
                    $disk->getSourceImage(),
                    ['content-type' => 'application/json'],
                    ''
                );
                /**
                 * @var \Google_Service_Compute_Image $image
                 */
                $image = $this->compute->getClient()->execute($request, \Google_Service_Compute_Image::class)->getDescription();


                if (strpos($image, 'Windows')) {
                    return true;
                }
            }
        }

        return false;
    }
}
