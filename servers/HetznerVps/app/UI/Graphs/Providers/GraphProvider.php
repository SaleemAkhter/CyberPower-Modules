<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Graphs\Providers;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

class GraphProvider extends BaseDataProvider implements ClientArea, AdminArea
{
    public function read()
    {
        $values = stripslashes(html_entity_decode($this->actionElementId));
        $values = json_decode($values, true);

        $this->data = $values;
    }

    public function update()
    {
        // TODO: Implement update() method.
    }
}