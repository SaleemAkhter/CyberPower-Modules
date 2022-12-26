<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BaseSection;

class ListSection extends BaseSection
{
    protected $items = [];

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return ListSection
     */
    public function setItems(array $items)
    {
        $this->items = $items;
        return $this;
    }
}
