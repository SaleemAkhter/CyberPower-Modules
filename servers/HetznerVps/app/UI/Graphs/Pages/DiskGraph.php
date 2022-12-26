<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Graphs\Pages;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Graphs\Line;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Graphs\Models\DataSet;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

class DiskGraph extends Line implements ClientArea, AdminArea
{

    use GraphData;

    protected $id = 'diskGraph';
    protected $name = 'diskGraph';
    protected $title = 'diskGraph';

    public function initContent()
    {
        $this->selectScope();
        $this->addSettingField($this->selectScope);
        //yAxes
        $this->updateChartScale('yAxes', [
            [
                'scaleLabel' => [
                    'display'     => true,
                    'labelString' => sl('lang')->T("Disk Usage")
                ],
                'ticks'      => [
                    'beginAtZero' => true
                ],
            ]]);
//        //Tooltip
//        $this->updateChartOption('tooltips', [
//            'callbacks' => [
//                'label' => 'mgTooltipCpu'
//            ]
//        ]);
    }

    public function prepareAjaxData()
    {
//        $rrData = $this->getApiData(  86400, 0, 3600)->getResponse();     // time 86400 -> 60sec * 60min * 24h
        $rrData = $this->getApiData()->getResponse();
        $read   = $rrData['metrics']->{'time_series'}->{'disk.0.bandwidth.read'}->{'values'};
        $write  = $rrData['metrics']->{'time_series'}->{'disk.0.bandwidth.write'}->{'values'};

        $labels   = [];
        $dataSets = [
            'diskread'  => [],
            'diskwrite' => [],
        ];
        $this->dateFormat();
        foreach ($read as $rrd)
        {
            $labels[]               = date($this->dateFormat, $rrd['0']);
            $dataSets['diskread'][] = round((isset($rrd['1']) ? $rrd['1'] : 0), 2);
        }

        foreach ($write as $rrd)
        {
//            $labels[] = date($dateFormat, $rrd['0']);
            $dataSets['diskwrite'][] = round((isset($rrd['1']) ? $rrd['1'] : 0), 2);
        }

        //Labels
        $this->setLabels($labels);
        //Memory
        $lang    = sl('lang');
        $dataSet = new DataSet();
        $dataSet->setTitle($lang->tr('Disk Read'))
            ->setData($dataSets['diskread'])
            ->setConfigurationDataSet([
                "backgroundColor" => 'rgba(174, 198, 57, 0.79)',
                "borderColor"     => 'rgba(174, 198, 57, 1)',
            ]);
        $this->addDataSet($dataSet);

        $dataSet = new DataSet();
        $dataSet->setTitle($lang->tr('Disk Write'))
            ->setData($dataSets['diskwrite'])
            ->setConfigurationDataSet([
                "backgroundColor" => 'rgba(39, 133, 134, 0.91)',
                "borderColor"     => 'rgba(39, 133, 134, 1)',
            ]);
        $this->addDataSet($dataSet);
    }
}