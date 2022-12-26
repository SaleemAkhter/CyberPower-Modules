<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Providers\Dedicated;

use Exception;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\WhmcsParams;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Dedicated\Server;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\Ovh;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\CustomFields;



/**
 * Description of Rebuild
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Reinstall extends BaseDataProvider implements ClientArea, AdminArea
{
    use WhmcsParamsApp;

    /**
     * @var Server
     */
    private $server;



    public function read()
    {
        $installationTemplates = $this->getApi()->dedicated->installationTemplate();

        $this->availableValues['osTemplate'] = $installationTemplates->getCompatibleTemplates($this->getApi()->dedicated->server()->one(), true);

        if($this->getRequestValue('mgformtype') == FormConstants::RELOAD)
        {
            $this->reload();
            return;
        }

        $first = reset($this->availableValues['osTemplate']);

        $languages = $installationTemplates->one($first)->model()->getAvailableLanguages(true);
        $this->availableValues['language'] = $languages;
    }

    public function create()
    {

    }

    public function delete()
    {

    }

    public function reload()
    {
        $this->data = $this->formData;
        $languages = $this->getApi()->dedicated->installationTemplate()->one($this->formData['osTemplate'])->model()->getAvailableLanguages(true);
        $this->availableValues['language'] = $languages;
    }


    public function update()
    {
        try
        {
            $serviceId = $this->getRequestValue('productselect');
            $serverId =CustomFields::get($serviceId, 'serverName|Server Name');

            $params = [
                'templateName' => $this->formData['osTemplate'],
                'details' => [
                    'language' =>$this->formData['language'],
                ]
            ];
            $this->getApi()->dedicated->server()->one($serverId)->install()->start($params);

        return (new HtmlDataJsonResponse())->setStatusSuccess()
                ->setMessageAndTranslate('rebootStarted')
                ->addData('refreshState', 'serverinformationTable')
                ->setCallBackFunction('refreshTable');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    /**
     * @return Api
     */
    private function getApi()
    {
        return Ovh::API($this->getAppWhmcsParams(WhmcsParams::getEssentialsKeys()));
    }
}
