<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\UserDomainComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\RawDataTable\RawDataTable;

class RawDataTableApi extends RawDataTable
{
    use DirectAdminAPIComponent;
    use UserDomainComponent;
}
