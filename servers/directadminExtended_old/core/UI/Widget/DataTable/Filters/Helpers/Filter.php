<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Filters\Helpers;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;

abstract class Filter extends BaseContainer implements FilterInterface
{
    protected $id = 'filter';
    protected $name = 'filter';
    protected $title = 'filterTitle';
}
