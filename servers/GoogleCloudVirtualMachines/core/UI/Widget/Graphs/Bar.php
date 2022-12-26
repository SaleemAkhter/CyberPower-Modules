<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Graphs;

/**
 * Description of EmptyGraph
 *
 * @author inbs
 */
class Bar extends EmptyGraph
{
    protected $id    = 'barGraph';
    protected $name  = 'barGraph';

    public function initContent()
    {
        parent::initContent();
        $this->setChartTypeToBar();
    }
}
