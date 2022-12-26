<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DataTableDropdownButtons;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\UserDomainComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataTable;

class DataTableApi extends DataTable
{
    use DirectAdminAPIComponent;
    use UserDomainComponent;
}
