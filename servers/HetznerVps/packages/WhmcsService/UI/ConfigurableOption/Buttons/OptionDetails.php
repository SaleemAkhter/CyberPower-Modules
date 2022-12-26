<?php


namespace ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\UI\ConfigurableOption\Buttons;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonRedirect;

class OptionDetails extends ButtonRedirect implements AdminArea
{
    protected $id = 'optionDetailsButton';
    protected $name = 'optionDetailsButton';
    protected $title = 'optionDetailsButtonTitle';

    public function initContent()
    {
        $this->addHtmlAttribute('v-if', "dataRow.exists");

        $this->rawUrl = 'configproductoptions.php?action=managegroup';
        $this->setRedirectParams(['id' => ':gid']);
    }
}
