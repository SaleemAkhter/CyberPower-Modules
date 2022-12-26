<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Providers;

use Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Ips\Ips;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\WhmcsParams;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Dedicated\IP\Repository;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
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
class IpDedicated extends BaseDataProvider implements ClientArea, AdminArea
{
    use WhmcsParamsApp;
    /**
     * @var Ips
     */
    private $manager;

    public function __construct()
    {
        parent::__construct();


        $params = $this->getWhmcsEssentialParams();

        $this->manager = new Repository(false, $params);
    }

    public function read()
    {
        $ip = $this->manager->get($this->actionElementId)->model()->toArray();

        $this->loadDataToForm($ip);
    }

    public function delete()
    {
        try
        {
            $this->manager->get($this->formData['id'])->remove();
            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('ipDeleted');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    public function update()
    {
        try
        {
            $id = $this->formData['id'];
            unset($this->formData['id']);
            $this->manager->get($id)->update($this->formData);
            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('ipUpdated');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    public function loadDataToForm($data)
    {
        $this->data += $data;
    }

    public function getDedicatedIps($reverse)
    {
        $dataManger = new Dedicated\IP\Repository(false, $this->getWhmcsParams());

        $data = $dataManger->getAllToArray();

        if(!$reverse)
        {
            return $data;
        }

        foreach ($data as &$ip)
        {
            $ip['reverse'] = $dataManger->findReverseForIp($ip);
//            $ip['id'] = $ip['fullIp']. "/" . $ip['reverse'] . '/' . $ip['ipAddress'];
        }

        return $data;
    }
}
