<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Graph\Pages;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Graphs\Models\DataSet;
use function  ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;

class CpuGraph extends AbstractGoogleGraph implements ClientArea
{
    protected $id = 'cpuGraph';
    protected $name = 'cpuGraph';
    protected $graphSettingsEnabled = false;

    public function initContent()
    {
        //yAxes
        $this->updateChartScale('yAxes', [
            [
                'scaleLabel' => [
                    'display' => true,
                    'labelString' => "Percentage (%)"
                ],
                'ticks' => [
                    'beginAtZero' => true
                ],
            ]]);
        //Tooltip
        $this->updateChartOption('tooltips', [
            'callbacks' => [
                'label' => 'mgTooltipCpu'
            ]
        ]);
    }

    public function prepareAjaxData()
    {
        $dataSets = [
            'cpu' => []
        ];

        $filter = 'metric.type="compute.googleapis.com/instance/cpu/utilization"';
        $result = $this->listTimeSeries($filter);


        foreach ($result->iterateAllElements() as $timeSeries) {
            foreach ($timeSeries->getPoints() as $point) {
                $dataSets['cpu'][] = $point->getValue()->getDoubleValue() * 100;
            }

            $entries = count($dataSets['cpu']);

            while ($entries <= 23){
                $dataSets['cpu'][] = 0;
                $entries++;
            }
        }

        $labels = $this->getTimeLabels();

        $reversedLabels = array_reverse($labels);
        $cpuSets = array_reverse($dataSets['cpu']);

        //Labels
        $this->setLabels($reversedLabels);
        //CPU Usage
        $lang = sl('lang');
        $dataSet = new DataSet();
        $dataSet->setTitle($lang->tr('CPU Usage'))
            ->setData($cpuSets)
            ->setConfigurationDataSet([
                "backgroundColor" => "rgba(174, 198, 57, 0.79)",
                "borderColor" => "rgba(174, 198, 57, 1)"
            ]);
        $this->addDataSet($dataSet);
    }
}