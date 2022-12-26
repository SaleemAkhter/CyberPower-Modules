<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Graphs;

/**
 * Description of EmptyGraph
 *
 * @author inbs
 */
class Radar extends EmptyGraph
{
    protected $id    = 'raderGraph';
    protected $name  = 'raderGraph';

    public function initContent()
    {
        parent::initContent();
        $this->setChartTypeToRader();
    }
}
