<?php


namespace ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\UI\ConfigurableOption\Buttons;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\UI\ConfigurableOption\Modals\AddOption as AddOptionModal;

class AddOption extends ButtonDataTableModalAction implements AdminArea
{
    protected $id = 'addOptionButton';
    protected $name = 'addOptionButton';
    protected $title = 'addOptionButtonTitle';

    protected $icon = 'lu-btn__icon lu-zmdi lu-zmdi-plus';

    public function initContent()
    {
        $this->addHtmlAttribute('v-if', "!dataRow.exists");

        $modal = new AddOptionModal();
        $this->initLoadModalAction($modal);
    }

}