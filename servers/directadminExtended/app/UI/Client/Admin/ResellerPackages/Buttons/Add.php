<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ResellerPackages\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Packages\Modals;

class Add extends ButtonCreate implements ClientArea
{
    protected $id    = 'addButton';
    protected $name  = 'addButton';
    protected $title = 'addButton';


    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'redirectToUrl(  \'' . $this->getAddPageUrl() . '\', $event)';
    }
    public function getAddPageUrl()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'ResellerPackages',
            'mg-action'=>'Add'
        ];

        return 'clientarea.php?'. \http_build_query($params);
    }
    public function returnAjaxData()
    {
        $this->setModal(new Modals\Add());

        return parent::returnAjaxData();
    }
}
