<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms\Fields;


use ModulesGarden\Servers\OvhVpsAndDedicated\Core\HandlerError\Exceptions\Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\Alerts;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\BaseField;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Dedicated;



class RemoteWorkLoaderWidget extends BaseField implements AdminArea, AjaxElementInterface, ClientArea
{
    use WhmcsParamsApp;
    use Alerts;

    const STATE_PENDING = 'pending';
    const STATE_LOADING = 'loading';
    const STATE_FINISHED = 'finished';

    protected $id = 'remoteWorkLoaderWidget';
    protected $name = 'remoteWorkLoaderWidget';
    protected $title = 'remoteWorkLoaderWidgetTitle';

    protected $state = self::STATE_LOADING;

    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-remote-work-loader';

    public function initContent()
    {
        $this->setInternalAlertMessage('loadingIpmi');
    }

    /**
     * overwrite this function to set state and data params
     */
    public function prepareAjaxData()
    {
        $repo         = new Dedicated\Repository($this->getWhmcsEssentialParams());
        $this->server = $repo->get();

        $type = $this->getRequestValue('type');
        $task = $this->getRequestValue('task');

        try
        {
            $result = $this->server->features()->ipmi()->getAccess($type);
            if($result['value'])
            {
                $this->state = self::STATE_FINISHED;
                $this->data['additionalData']['redirectUrl'] = $result['value'];
                return;
            }
        }
        catch (\Exception $exception)
        {

        }

        $taskStatus = $this->server->task()->one($task)->model()->getStatus();

        if($taskStatus == 'done')
        {
            $result = $this->server->features()->ipmi()->getAccess($type);

            $this->state = self::STATE_FINISHED;
            $this->data['additionalData']['redirectUrl'] = $result['value'];
        }

    }

    /**
     * do not overwrite this function
     * @return type \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates\RawDataJsonResponse
     */
    public function returnAjaxData()
    {
        $this->prepareAjaxData();

        $returnData = [
            'state' => $this->state,
            'additionalData' => $this->data['additionalData']
        ];

        return (new ResponseTemplates\RawDataJsonResponse($returnData))->setCallBackFunction($this->callBackFunction);
    }
}
