<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Graphs;

/**
 * Description of EmptyGraph
 *
 * @author inbs
 */
class Doughnut extends EmptyGraph
{
    protected $id    = 'doughnutGraph';
    protected $name  = 'doughnutGraph';
    
    public function initContent()
    {
        parent::initContent();
        $this->setChartTypeToDoughnut();
    }
}
