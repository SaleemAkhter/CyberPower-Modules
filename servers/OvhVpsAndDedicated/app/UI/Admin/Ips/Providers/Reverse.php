<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Providers;

use Exception;
use GuzzleHttpOvh\Exception\ClientException;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\Ovh;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
/**
 * Description of Rebuild
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Reverse extends BaseDataProvider implements ClientArea, AdminArea
{
    use WhmcsParamsApp;
    /**
     * @var Api
     */
    protected $api;

    public function read()
    {
        if(!$data = $this->getRequestValue('actionElementId'))
        {
            return;
        }

        $data = base64_decode($data);
        $data = \json_decode($data);

        $this->data['id'] = $data->fullIp;
        $this->data['ipReverse'] = $data->ipAddress;
        $this->data['ipReverseMock'] = $data->ipAddress;
        $this->data['reverse'] = $data->reverse != '-' ? $data->reverse : '';
    }

    public function delete()
    {

    }

    public function update()
    {
        try
        {
            $params = $this->formData;
            unset($params['id']);

            $this->api = Ovh::API($this->getWhmcsEssentialParams());
            $res =  $this->api->ip->one($this->formData['id'])->reverse()->add($params);

            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('ipReverseChangedSuccess');
        }
        catch (ClientException $ex)
        {
            $message = $ex->getMessage();
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($message);
        }
    }
}
