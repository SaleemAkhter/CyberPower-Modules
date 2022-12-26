<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\DataTable\Filters;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\DataTable\Filters\Helpers\Filter;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\DataTable\Filters\Helpers\FilterInterface;

class Select extends \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Fields\Select implements FilterInterface
{
    protected $id = 'selectFilter';
    protected $name = 'selectFilter';
    protected $title = 'selectFilterTitle';

    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-dt-select-filter';

    protected $parentId = null;

    public function setParentId($id)
    {
        $this->parentId = $id;
    }

    /**
     * @return null
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    public function isVueRegistrationAllowed()
    {
        if ($this->getRequestValue('loadData') === $this->getParentId() && $this->getRequestValue('ajax') == 1)
        {
            return false;
        }

        return true;
    }

}
