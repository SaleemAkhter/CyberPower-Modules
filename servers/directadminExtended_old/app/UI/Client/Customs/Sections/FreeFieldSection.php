<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BaseSection;

class FreeFieldSection extends BaseSection implements ClientArea, AdminArea
{
    protected $id   = 'freeFieldsSection';
    protected $name = 'freeFieldsSection';
}