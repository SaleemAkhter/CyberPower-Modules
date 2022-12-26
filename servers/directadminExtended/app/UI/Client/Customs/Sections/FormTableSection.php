<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BoxSection;

class FormTableSection extends BoxSection
{
    protected $items = [];
    protected $containerClasses='col-lg-12';
    protected $headers=[];


    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $items
     * @return ListSection
     */
    public function setHeaders(array $header)
    {
        $this->headers = $header;
        return $this;
    }

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
     public function setContainerClasss($classes)
    {
        $this->containerClasses = implode(" ",$classes);
        return $this;
    }
    public function getContainerClasss()
    {
        return $this->containerClasses;
    }
}
