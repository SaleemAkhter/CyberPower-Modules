<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Packages\WhmcsService\UI\ConfigurableOption\Buttons;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Packages\WhmcsService\UI\ConfigurableOption\Modals\AddOptions as AddOptionModals;

class AddOptions extends ButtonCreate implements AdminArea
{
    protected $id = 'addOptionsButton';
    protected $name = 'addOptionsButton';
    protected $title = 'addOptionButtonsTitle';

    public function initContent()
    {
        $this->replaceClass('lu-btn--primary', 'lu-btn--success');

        $modal = new AddOptionModals();
        $this->initLoadModalAction($modal);
    }

}