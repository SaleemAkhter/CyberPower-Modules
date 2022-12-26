<?php
/**
 * Created by PhpStorm.
 * User: INBS
 * Date: 23.09.2019
 * Time: 11:23
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Buttons\MassAction;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Modals;

class Delete extends ButtonMassAction implements ClientArea
{
    protected $id    = 'deleteManyButton';
    protected $name  = 'deleteManyButton';
    protected $title = 'deleteManyButton';
    protected $icon  = 'icon-in-button lu-zmdi lu-zmdi-delete';

    public function initContent()
    {
        $this->switchToRemoveBtn();
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\MassAction\Delete());

        return parent::returnAjaxData();
    }
}