<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MxRecords\Modals;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MxRecords\Forms\Edit as Form;

class Edit extends BaseEditModal implements ClientArea
{
    protected $id    = 'editRecordModal';
    protected $name  = 'editRecordModal';
    protected $title = 'editRecordModal';

    public function initContent()
    {
        $this->addForm(new Form());
    }
}
