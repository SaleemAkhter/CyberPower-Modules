<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Fields;

use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\AjaxFields\Select;


class ActionSelect extends Select implements AdminArea
{
    public function prepareAjaxData()
    {
        $this->setAvailableValues([
            ['key' => '2', 'value' => 'Installatron'],
            ['key' => '1', 'value' => 'Softaculous']
        ]);

        // '2' for single, ['1', '2'] for multiple
        //$this->setSelectedValue('2');
    }
}
