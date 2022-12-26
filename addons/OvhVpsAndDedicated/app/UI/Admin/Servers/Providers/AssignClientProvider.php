<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Providers;

use ModulesGarden\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Utilities\ServerStrategyProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\CustomFields;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\ServiceManager;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;

/**
 * Class AssignClientProvider
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class AssignClientProvider extends BaseModelDataProvider implements AdminArea
{
    const OVH_NAME_FIELD = 'serverName|Server Name';

    use WhmcsParamsApp;
    use RequestObjectHandler;
    use Lang;

    public function __construct()
    {
        parent::__construct(null);
    }

    public function read()
    {
        $this->data['id'] = $this->getActionElementIdValue();
    }


    public function update()
    {
        $this->loadLang();
        try
        {

            $form = $this->getFormDataValues();

            if ($form['clientRemoteSearch'] == null)
            {
                return (new HtmlDataJsonResponse())->setMessage($this->lang->translate('assignClientProvider', 'update', 'service', 'error', 'noClient'))->setStatusError();
            }

            if ($form['service'] == null)
            {
                return (new HtmlDataJsonResponse())->setMessage($this->lang->translate('assignClientProvider', 'update', 'service', 'error', 'noService'))->setStatusError();
            }

            $fieldValue = CustomFields::get($form['service'], self::OVH_NAME_FIELD);
            if (trim($fieldValue) != '')
            {
                return (new HtmlDataJsonResponse())->setMessage($this->lang->translate('assignClientProvider', 'update', 'service', 'error', 'hasMachine'))->setStatusError();
            }

            CustomFields::set($form['service'], self::OVH_NAME_FIELD, $form['id']);


            $params = ServiceManager::getWhmcsParamsByHostingId($form['service']);
            $serverStrategy = new ServerStrategyProvider();
            $serverStrategy->chooseServer(new Client($params));
            $server = $serverStrategy->getServer();
            $server->assignDomainAndIpToService();

            return (new HtmlDataJsonResponse())->setMessage($this->lang->translate('assignClientProvider', 'update', 'service', 'success'));
        }
        catch (\Exception $exception)
        {
            return (new HtmlDataJsonResponse())->setMessage($exception->getMessage())->setStatusError();
        }

    }
}
