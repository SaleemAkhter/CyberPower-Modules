<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Packages\WhmcsService\UI\ConfigurableOption\Buttons;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonRedirect;

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
