<?php


namespace ModulesGarden\Servers\VultrVps\Packages\WhmcsService\UI\ConfigurableOption\Buttons;

use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\VultrVps\Packages\WhmcsService\UI\ConfigurableOption\Modals\AddOptions as AddOptionModals;

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