<?php


namespace ModulesGarden\Servers\AwsEc2\Packages\WhmcsService\UI\ConfigurableOption\Buttons;

use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\AwsEc2\Packages\WhmcsService\UI\ConfigurableOption\Modals\AddOption as AddOptionModal;

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