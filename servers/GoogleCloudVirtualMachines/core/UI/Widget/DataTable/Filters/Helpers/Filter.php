<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\DataTable\Filters\Helpers;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Builder\BaseContainer;

abstract class Filter extends BaseContainer implements FilterInterface
{
    protected $id = 'filter';
    protected $name = 'filter';
    protected $title = 'filterTitle';
}
