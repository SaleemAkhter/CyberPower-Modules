<?php


namespace ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\UI\ConfigurableOption\Modals;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\UI\ConfigurableOption\Forms\AddOptions as AddOptionsForm;

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
