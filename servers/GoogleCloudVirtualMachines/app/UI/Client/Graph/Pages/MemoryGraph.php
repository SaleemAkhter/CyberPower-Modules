<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Graph\Pages;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Graphs\Models\DataSet;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;

class MemoryGraph extends AbstractGoogleGraph implements ClientArea
{
    protected $id = 'memoryGraph';
    protected $name = 'memoryGraph';
    protected $graphSettingsEnabled = false;

    public function initContent()
    {
        //yAxes
        $this->updateChartScale('yAxes', [
            [
                'scaleLabel' => [
                    'display' => true,
                    'labelString' => "MB"
                ],
                'ticks' => [
                    'beginAtZero' => true
                ],
            ]]);
        //Tooltip
        $this->updateChartOption('tooltips', [
            'callbacks' => [
                'label' => 'mgTooltipMemory'
            ]
        ]);
    }

    public function prepareAjaxData()
    {
        $dataSets = [
            'memory' => [],
            'maxMemory' => []
        ];

        $memoryUsedFilter = 'metric.type="compute.googleapis.com/instance/memory/balloon/ram_used"';
        $maxMemoryFilter = 'metric.type="compute.googleapis.com/instance/memory/balloon/ram_size"';

        $memoryUsed = $this->listTimeSeries($memoryUsedFilter);
        $maxMemory = $this->listTimeSeries($maxMemoryFilter);

        foreach ($maxMemory->iterateAllElements() as $timeSeries) {
            foreach ($timeSeries->getPoints() as $point) {
                $dataSets['maxMemory'][] = $point->getValue()->getDoubleValue()/1000000;
            }
        }

        foreach ($memoryUsed->iterateAllElements() as $timeSeries) {
            foreach ($timeSeries->getPoints() as $point) {
                $dataSets['memory'][] = $point->getValue()->getDoubleValue()/1000000;
            }
            $entries = count($dataSets['memory']);

            while ($entries <= 23){
                $dataSets['memory'][] = 0;
                $dataSets['maxMemory'][] = 0;
                $entries++;
            }
        }

        $labels = $this->getTimeLabels();

        $reversedLabels = array_reverse($labels);
        $reverseMemory = array_reverse($dataSets['memory']);
        $reverseMaxMemory = array_reverse($dataSets['maxMemory']);

        //Labels
        $this->setLabels($reversedLabels);

        //Memory Usage
        $lang = sl('lang');
        $dataSet = new DataSet();
        $dataSet->setTitle($lang->tr('Memory Usage'))
            ->setData($reverseMemory)
            ->setConfigurationDataSet([
                "backgroundColor" => 'rgba(39, 133, 134, 0.91)',
                "borderColor"     => 'rgba(39, 133, 134, 1)',
            ]);
        $this->addDataSet($dataSet);

        //Total
        $dataSet = new DataSet();
        $dataSet->setTitle($lang->tr('Total'))
            ->setData($reverseMaxMemory)
            ->setConfigurationDataSet([
                "backgroundColor" => 'rgba(174, 198, 57, 0.79)',
                "borderColor" => 'rgba(174, 198, 57, 1)',
            ]);
        $this->addDataSet($dataSet);
    }
}