<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ErrorPages\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;

use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonAjaxCustomAction;

class View extends ButtonAjaxCustomAction implements ClientArea
{
    use DirectAdminAPIComponent;

    protected $id    = 'viewButton';
    protected $name  = 'viewButton';
    protected $title = 'viewButton';
    protected $class = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-btn--default lu-tooltip'];
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-eye';

    public function initContent()
    {
        $this->customActionName = 'viewPageContent';
        $this->htmlAttributes['@click'] = 'makeCustomAction(\'' . $this->customActionName . '\', ' . $this->parseCustomParams() . ', $event, \'' . $this->getNamespace() . '\', dataRow.id)';
        $this->htmlAttributes['data-toggle'] = 'lu-tooltip';

    }

    public function returnAjaxData()
    {
        $this->loadUserApi();

        $pageId = Request::build()->get('pageId');
        $data   = [
            'path'  => '/domains/' . $this->getWhmcsParamByKey('domain') . '/public_html/' . $pageId
        ];

        // IF File does not exist at all
        try
        {
            $response = $this->userApi->fileManager->view(new Models\Command\FileManager($data))->first();
            $content  = $response->getText();
        }
        catch (\Exception $ex)
        {
            $content = '';
        }

        return (new ResponseTemplates\RawDataJsonResponse())->addData('content', $content);
    }
}
