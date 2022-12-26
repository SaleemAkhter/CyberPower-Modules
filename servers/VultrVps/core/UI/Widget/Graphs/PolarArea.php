<?php

namespace ModulesGarden\Servers\VultrVps\Core\UI\Widget\Graphs;

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
