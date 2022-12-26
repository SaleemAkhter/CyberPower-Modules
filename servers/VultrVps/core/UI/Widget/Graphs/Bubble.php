<?php

namespace ModulesGarden\Servers\VultrVps\Core\UI\Widget\Graphs;

/**
 * Description of EmptyGraph
 *
 * @author inbs
 */
class Bubble extends EmptyGraph
{
    protected $id    = 'bubbleGraph';
    protected $name  = 'bubbleGraph';

    public function initContent()
    {
        parent::initContent();
        $this->setChartTypeToBubble();
    }
}
