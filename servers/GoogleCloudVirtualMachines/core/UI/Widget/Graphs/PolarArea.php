<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Graphs;

/**
 * Description of EmptyGraph
 *
 * @author inbs
 */
class PolarArea extends EmptyGraph
{
    protected $id    = 'polarAreaGraph';
    protected $name  = 'polarAreaGraph';

    public function initContent()
    {
        parent::initContent();
        $this->setChartTypeToPolarArea();
    }
}
