<?php

/**********************************************************************
 * VultrVps product developed. (2019-04-26)
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

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Pages;


use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\App\Api\Models\Instance;
use ModulesGarden\Servers\VultrVps\App\Traits\ProductSetting;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Buttons\ConsoleButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Buttons\ManagamentButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Buttons\Reboot;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Buttons\ReinstallButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Buttons\Start;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Buttons\Stop;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Fields\PasswordHiden;
use ModulesGarden\Servers\VultrVps\Core\Traits\IsAdmin;
use ModulesGarden\Servers\VultrVps\Core\Traits\Lang;
use ModulesGarden\Servers\VultrVps\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\Servers\VultrVps\Core\UI\Traits\WhmcsParams;

class StatusWidget extends BaseContainer implements ClientArea, AdminArea, AjaxElementInterface
{
    use IsAdmin;
    use Lang;
    use ProductSetting;
    use WhmcsParams;

    const STATUS               = 'status';
    const STATUS_COLOR         = 'statusColor';
    const STATUS_COLOR_SUCCESS = 'success';
    const STATUS_COLOR_DEFAULT = 'default';
    const STATUS_COLOR_WARNING = 'warning';
    const STATUS_COLOR_DANGER  = 'danger';
    const API_ERROR = 'apiError';
    const BUTTONS_STATES        = 'buttonsStates';
    const BUTTON_ID             = 'buttonId';
    const BUTTON_STATE          = 'buttonState';
    const BUTTON_STATE_ACTIVE   = 'active';
    const BUTTON_STATE_HIDDEN   = 'hidden';
    const BUTTON_STATE_DISABLED = 'disabled';
    const INSTANCE_DETAILS = 'instanceDetails';
    /**
     * @var Instance
     */
    protected $instance;
    protected $id = 'statusWidget';
    protected $name = 'statusWidget';
    protected $title = 'statusWidgetTitle';
    protected $managamentButtons=[];

    protected $vueComponent = true;
    protected $defaultVueComponentName = 'mg-service-actions';

    public function initContent()
    {
        $this->customTplVars['isAdmin'] = $this->isAdmin();

        $this->addButton(Start::class);
        $this->addButton(Stop::class);
        $this->addButton(Reboot::class);
        $this->addButton(new ReinstallButton());
        //console
        if($this->productSetting()->hasPermission('console')){
            $this->addButton(new ConsoleButton());
        }
        //backup
        if($this->productSetting()->hasPermission('backup')){
            $this->addManagamentButton((new ManagamentButton('backup')));
        }
        //firewall
        if($this->productSetting()->hasPermission('firewall')){
            $this->addManagamentButton((new ManagamentButton('firewall')));
        }
        //bandwidth
        if($this->productSetting()->hasPermission('bandwidth')){
            $this->addManagamentButton((new ManagamentButton('bandwidth')));
        }
        //changeOs
        if($this->productSetting()->hasPermission('changeOs')){
            $this->addManagamentButton((new ManagamentButton('changeOs')));
        }
        //snapshot
        if($this->productSetting()->hasPermission('snapshot')){
            $this->addManagamentButton((new ManagamentButton('snapshot')));
        }


    }

    public function returnAjaxData()
    {
        return (new RawDataJsonResponse($this->prepareAjaxData()))->setCallBackFunction($this->callBackFunction)->setRefreshTargetIds((array)$this->refreshActionIds);
    }

    /**
     * Should always return an array for the ajax call, 'status' param is required
     */
    public function prepareAjaxData()
    {

        try
        {
            $this->instance = (new InstanceFactory())->fromWhmcsParams();
            $instanceData   = $this->instance->details();
            $ipv4 = $this->instance->ipv4();
            $status         = $this->instance->getPowerStatus();
        }
        catch (\Exception $exc)
        {
            return [
                self::API_ERROR        => $exc->getMessage(),
                self::BUTTONS_STATES   => $this->getButtonStates('unknown'),
                self::STATUS           => 'unknown',
                self::STATUS_COLOR     => self::STATUS_COLOR_DANGER,
                self::INSTANCE_DETAILS => []
            ];
        }

        return [
            self::STATUS           => $status,
            self::BUTTONS_STATES   => $this->getButtonStates($status),
            self::STATUS_COLOR     => $this->getStatusColor($status),
            self::INSTANCE_DETAILS => $this->parseInstanceDetails($instanceData,$ipv4)
        ];
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

            $states[$button->getId()] = self::BUTTON_STATE_ACTIVE;
        }

        return $states;
    }

    public function getStatusColor($status)
    {
        if ($status === 'running')
        {
            return self::STATUS_COLOR_SUCCESS;
        }

        if ($status === 'starting')
        {
            return self::STATUS_COLOR_WARNING;
        }

        if ($status === 'stopped')
        {
            return self::STATUS_COLOR_DEFAULT;
        }

        return self::STATUS_COLOR_DANGER;
    }

    public function parseInstanceDetails($instanceData = [],$ipv4=[])
    {
        if (!is_object($instanceData))
        {
            return [];
        }
        $this->loadLang();
        $details = [];
        $isAdmin = $this->isAdmin();
        $password = new PasswordHiden();
        $password->setValue($this->getWhmcsParamByKey('password'));
        //InstanceDetails
        $details['InstanceDetails'] = [
            'title'   => $this->lang->translate('statusWidget', 'InstanceDetailsTitle'),
            'details' => [
                [
                    'key'      => $this->lang->translate('Status'),
                    'value'    => $this->lang->translate($instanceData->instance->power_status),
                    'isStatus' => true
                ],
                [
                    'key'   => $this->lang->translate('Password'),
                    'value' => $password->getHtml(),
                ],
                [
                    'key'   => $this->lang->translate('OS'),
                    'value' => ucfirst($instanceData->instance->os),
                ],
                [
                    'key'   => $this->lang->translate('Region'),
                    'value' => $this->lang->abtr('region',$instanceData->instance->region),
                ],
                [
                    'key'   => $this->lang->translate('CPU'),
                    'value' =>sprintf("%d vCore",$instanceData->instance->vcpu_count )
                ],
                [
                    'key'   => $this->lang->translate('RAM'),
                    'value' => sprintf("%d MB", $instanceData->instance->ram),
                ],
                [
                    'key'   => $this->lang->translate('Storage'),
                    'value' => sprintf("%d GB", $instanceData->instance->disk),
                ],
            ]
        ];
        //network interfaces

        foreach ($ipv4->ipv4s as $k => $ip){
            $label = $ip->type == "main_ip" ? $this->lang->translate('Main IP Address') : $this->lang->translate('IP Address');
            $details['NetworkInterfaceIpv4'.$k] = [
                'title' => $this->lang->translate('statusWidget', 'Public Network IPv4') ,
                'details' => [
                    [
                        'key' => $label,
                        'value' => $ip->ip
                    ],
                    [
                        'key' => $this->lang->translate('Netmask'),
                        'value' => $ip->netmask
                    ],
                    [
                        'key' => $this->lang->translate('Gateway'),
                        'value' => $ip->gateway
                    ],
                ]
            ];
        }


        if($instanceData->instance->v6_main_ip){
            $details['NetworkInterfaceIpv6'] = [
                'title' => $this->lang->translate('statusWidget', 'Public Network IPv6') ,
                'details' => [
                    [
                        'key' => $this->lang->translate('IP Address'),
                        'value' => $instanceData->instance->v6_main_ip
                    ],
                    [
                        'key' => $this->lang->translate('Network'),
                        'value' => $instanceData->instance->v6_network
                    ],
                    [
                        'key' => $this->lang->translate('Netmask'),
                        'value' => $instanceData->instance->v6_network_size
                    ],
                    [
                        'key' => $this->lang->translate('Gateway'),
                        'value' => $this->lang->translate('(use router discovery)')
                    ],
                ]
            ];
        }

        return $details;
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

    public function addManagamentButton($button)
    {
        $button->initContent();
        $this->managamentButtons[] = $button;
        return $this;
    }

    public function getManagamentButtons()
    {
        return $this->managamentButtons;
    }
}
