<?php


namespace ModulesGarden\Servers\VultrVps\Packages\WhmcsService\UI\ConfigurableOption\Modals;

use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\VultrVps\Packages\WhmcsService\UI\ConfigurableOption\Forms\AddOptions as AddOptionsForm;

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
