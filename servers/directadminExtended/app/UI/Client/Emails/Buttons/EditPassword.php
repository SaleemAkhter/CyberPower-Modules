<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\App\Models\Hosting;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Modals;

class EditPassword extends ButtonDataTableModalAction implements ClientArea
{
    use ProductsFeatureComponent;
    use RequestObjectHandler;

    protected $id    = 'editPassword';
    protected $name  = 'editPassword';
    protected $title = 'editPassword';
    protected $icon = 'lu-zmdi lu-zmdi-lock';

    public function initContent()
    {
        $this->addHtmlAttribute('@click', 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [dataRow.id,dataRow.usage,dataRow.limit], null, true)');

        $model = new Hosting();
        $pid = $model->getProductIdByHostingId($this->getRequestValue('id'));

        if(! $this->isFeatureEnabled('change_password', $pid))
        {
            $this->setDisableByColumnValue('main', true);
        }
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\EditPassword());
        return parent::returnAjaxData();
    }
}