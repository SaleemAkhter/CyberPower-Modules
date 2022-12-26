<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Packages\WhmcsService\UI\ConfigurableOption\Modals;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Packages\WhmcsService\UI\ConfigurableOption\Forms\AddOptions as AddOptionsForm;

class AddOptions extends BaseEditModal implements AdminArea
{
    protected $id = 'addOptionsModal';
    protected $name = 'addOptionsModal';
    protected $title = 'addOptionsModalTitle';

    public function initContent()
    {
        $form = new AddOptionsForm();
        $this->addForm($form);
    }
}
