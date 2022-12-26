<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Providers\Dedicated;

use Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\WhmcsParams;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Dedicated;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\FileReader\Reader\Html;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ServiceLocator;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Dedicated\Server;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;


/**
 * Description of Rebuild
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Ipmi extends BaseDataProvider implements ClientArea, AdminArea
{
    use Lang;
    use WhmcsParamsApp;
    /**
     * @var Server
     */
    private $server = null;

    public function __construct()
    {
        parent::__construct();
        $this->loadLang();
    }

    public function read()
    {
        if($this->getRequestValue('mgFormData') == FormConstants::RELOAD)
        {
            $this->data = $this->formData;
            return;
        }

        $this->availableValues['type'] = $this->getSupportedIpmiTypes();
        $this->availableValues['ttl'] = $this->getTTL();
    }

    public function create()
    {

    }

    public function delete()
    {

    }

    public function reload()
    {

//        $this->data['type'] = $this->formData['type'];

    }


    public function update()
    {
//        return (new HtmlDataJsonResponse())->setStatusSuccess()
//            ->setMessageAndTranslate('rebootStarted')
//            ->addData('refreshState', 'serverinformationTable')
//            ->addData('namespace', __NAMESPACE__)
//            ->setCallBackFunction('ipmiAccess');
//        die('update Provider');
//        try
//        {
//            $bootObject = $this->getServer()->boot();
//            $boots = $bootObject->allToModel($this->formData['type']);
//            $chosen = $boots[0];
//
//            $params = [
//                'bootId' => $chosen->getBootId(),
//                'monitoring' => $this->formData['monitoring'] == 'on' ? true : false,
//            ];
//
//            $this->getServer()->update($params);
//
//            return (new HtmlDataJsonResponse())->setStatusSuccess()
//                ->setMessageAndTranslate('rebootStarted')
//                ->addData('refreshState', 'serverinformationTable')
//                ->setCallBackFunction('refreshTable');
//        }
//        catch (Exception $ex)
//        {
//            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
//        }
    }

    /**
     * @return Server
     */
    private function getServer()
    {
        if ($this->server)
        {
            return $this->server;
        }

        $repo         = new Dedicated\Repository($this->getAppParams(WhmcsParams::getEssentialsKeys()));
        $this->server = $repo->get();

        return $this->server;
    }

    /**
     * @return mixed
     */
    private function getSupportedIpmiTypes()
    {
        $wanted = ['serialOverLanURL', 'kvmipHtml5URL'];
        $ipmiFeatures = $this->getServer()->features()->ipmi()->model()->getEnabledSupportedFeatures();

        foreach ($ipmiFeatures as $featureName => &$feature)
        {
            if(!in_array($featureName, $wanted))
            {
                unset($ipmiFeatures[$featureName]);
            }
            $feature = $this->lang->absoluteTranslate('server', 'dedicated', 'ipmi', 'feature', $featureName);
        }

        return $ipmiFeatures;
    }

    /**
     * Return available ttl properties
     */
    private function getTTL()
    {
        return [
          1 => 1,
          3 => 3,
          5 => 5,
          10 => 10,
          15 => 15
        ];
    }
}
