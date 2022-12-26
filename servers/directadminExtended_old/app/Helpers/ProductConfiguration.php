<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;

class ProductConfiguration
{
    public function getConfig()
    {
        $content = sl('adminProductPage')->execute();
        echo json_encode(['content' => "<tr><td>" . $content . "</td></tr>", 'mode' => 'advanced']);
        die();
    }

}
