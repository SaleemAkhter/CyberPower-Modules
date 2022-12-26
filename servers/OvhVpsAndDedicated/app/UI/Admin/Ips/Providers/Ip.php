<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Providers;

use Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Ips\Ips;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\OvhApiFactory;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\IP\Repository;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Dedicated;

/**
 * Description of Rebuild
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Ip extends BaseDataProvider implements ClientArea, AdminArea
{
    use WhmcsParamsApp;
    use Lang;
    /**
     * @var Ips
     */
    private $manager;

    public function __construct()
    {
        parent::__construct();
        $this->loadLang();
    }

    public function read()
    {
        if(!$actionElement = $this->getActionElementIdValue())
        {
            return;
        }
        $actionValues = \json_decode(html_entity_decode($actionElement));
        $this->loadDataToForm((array) $actionValues);
    }

    public function delete()
    {
        try
        {
            $this->manager = new Repository(false, $this->getWhmcsEssentialParams());

            $this->manager->get($this->formData['id'])->remove();

            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessage($this->lang->translate('ipProvider', 'delete', 'success'));
        }
        catch (\Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    public function update()
    {
        try
        {
            $ipAddress = $this->formData['ipAddress'];
            unset($this->formData['ipAddress']);
            $api = (new OvhApiFactory())->formParams();
            if(!$this->formData['reverse']){
                $response = $api->get(sprintf("/ip/%s/reverse/%s",$ipAddress,$ipAddress ));
                if($response['reverse']){
                    $response = $api->delete(sprintf("/ip/%s/reverse/%s",$ipAddress,$ipAddress ));
                }
                return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessage($this->lang->translate('ipProvider', 'update', 'success'));
            }

            $api->post(sprintf("/ip/%s/reverse",$ipAddress),
                [
                    'ipReverse' => $ipAddress,
                    'reverse' => $this->formData['reverse']
                    ]);

            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessage($this->lang->translate('ipProvider', 'update', 'success'));
        }
        catch (\Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    public function loadDataToForm($data)
    {
        $this->data += $data;
        $this->data['ipAddressMock'] = $this->data['ipAddress'];
    }

    public function getDedicatedIps()
    {
        $dataManger = new Dedicated\IP\Repository(false, $this->getWhmcsParams());

        $data = $dataManger->getAllToArray();

        foreach ($data as &$ip)
        {
            $ip['reverse'] = $dataManger->findReverseForIp($ip);
//            $ip['id'] = $ip['fullIp']. "/" . $ip['reverse'] . '/' . $ip['ipAddress'];
        }

        return $data;
    }
}
