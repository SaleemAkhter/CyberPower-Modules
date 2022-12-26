<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Graphs\Pages;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Graphs\Line;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Graphs\Models\DataSet;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

class CpuGraph extends Line implements ClientArea, AdminArea
{

    use GraphData;

    protected $id = 'cpuGraph';
    protected $name = 'cpuGraph';
    protected $title = 'cpuGraph';
    protected $graphSettingsEnabled = false;
    protected $graphSettingsKey = 'cpuGraphSettings';

    public function initContent()
    {
       // $this->selectScope();
        //$this->addSettingField($this->selectScope);
//      yAxes
        $this->updateChartScale('yAxes', [
            [
                'scaleLabel' => [
                    'display'     => true,
                    'labelString' => sl('lang')->T("CPU Use")
                ],
                'ticks'      => [
                    'beginAtZero' => true
                ],
            ]]);
////        Tooltip
//        $this->updateChartOption('tooltips', [
//            'callbacks' => [
//                'label' => 'mgTooltipCpu'
//            ]
//        ]);
    }

    public function prepareAjaxData()
    {
       /* if ($this->configurationFields['timeframe'])
        {
            $this->timeframe = $this->configurationFields['timeframe'];
        }*/
//        $rrData = $this->getApiData( 86400, 0, 120)->getResponse();     // time 86400 -> 60sec * 60min * 24h
        $rrData   = $this->getApiData()->getResponse();
        $cpu      = $rrData['metrics']->{'time_series'}->{'cpu'}->{'values'};
        $labels   = [];
        $dataSets = [
            'cpu' => []
        ];
        $this->dateFormat();
        foreach ($cpu as $rrd)
        {
            $labels[] = date($this->dateFormat, $rrd['0']);
//            $labels[]           = date('r', $rrd['0']);
            $dataSets['cpu'][] = round((isset($rrd['1']) ? (float)$rrd['1'] : 0) * 100, 2);
        }

        //Labels
        $this->setLabels($labels);
        //CPU Usage
        $lang    = sl('lang');
        $dataSet = new DataSet();
        $dataSet->setTitle($lang->tr('CPU Usage'))
            ->setData($dataSets['cpu'])
            ->setConfigurationDataSet([
                "backgroundColor" => "rgba(174, 198, 57, 0.79)",
                "borderColor"     => "rgba(174, 198, 57, 1)"
            ]);
        $this->addDataSet($dataSet);
    }
}