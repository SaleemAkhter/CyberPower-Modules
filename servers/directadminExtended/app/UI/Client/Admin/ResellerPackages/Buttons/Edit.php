<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ResellerPackages\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ResellerPackages\Modals;

class Edit extends ButtonDataTableModalAction implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\DisableButtonByColumnValue;


    protected $id    = 'editButton';
    protected $name  = 'editButton';
    protected $title = 'editButton';

    protected $icon = '';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'redirectToEditUrl(  \'' . $this->getAddPageUrl() . '\', $event)';
    }
    public function getAddPageUrl()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'ResellerPackages',
            'mg-action'=>'Edit'
        ];

        return 'clientarea.php?'. \http_build_query($params);
    }
    public function returnAjaxData()
    {
        $this->setModal(new Modals\Edit());
        return parent::returnAjaxData();
    }
}

