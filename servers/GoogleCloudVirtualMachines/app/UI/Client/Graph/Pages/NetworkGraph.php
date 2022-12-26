<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Graph\Pages;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Graphs\Models\DataSet;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;

class NetworkGraph extends AbstractGoogleGraph implements ClientArea
{
    protected $id = 'networkGraph';
    protected $name = 'networkGraph';
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
                'label' => 'mgTooltipNetwork'
            ]
        ]);
    }

    public function prepareAjaxData()
    {
        $dataSets = [
            'bytesIn' => [],
            'bytesOut' => []
        ];

        $filterIn = 'metric.type="compute.googleapis.com/instance/network/received_bytes_count"';
        $filterOut = 'metric.type="compute.googleapis.com/instance/network/sent_bytes_count"';

        $resultIn = $this->listTimeSeries($filterIn);
        $resultOut = $this->listTimeSeries($filterOut);

        foreach ($resultIn->iterateAllElements() as $timeSeries) {
            foreach ($timeSeries->getPoints() as $point) {
                $dataSets['bytesIn'][] = $point->getValue()->getDoubleValue()/1000000;
            }
        }

        foreach ($resultOut->iterateAllElements() as $timeSeries) {
            foreach ($timeSeries->getPoints() as $point) {
                $dataSets['bytesOut'][] = $point->getValue()->getDoubleValue()/1000000;
            }

            $entries = count($dataSets['bytesOut']);

            while ($entries <= 23){
                $dataSets['bytesOut'][] = 0;
                $dataSets['bytesIn'][] = 0;
                $entries++;
            }
        }

        $labels = $this->getTimeLabels();

        $reversedLabels = array_reverse($labels);
        $reverseBytesIn = array_reverse($dataSets['bytesIn']);
        $reverseBytesOut = array_reverse($dataSets['bytesOut']);

        //Labels
        $this->setLabels($reversedLabels);

        //Memory Usage
        $lang = sl('lang');
        $dataSet = new DataSet();
        $dataSet->setTitle($lang->tr('Received MB'))
            ->setData($reverseBytesIn)
            ->setConfigurationDataSet([
                "backgroundColor" => 'rgba(39, 133, 134, 0.91)',
                "borderColor"     => 'rgba(39, 133, 134, 1)',
            ]);
        $this->addDataSet($dataSet);

        //Total
        $dataSet = new DataSet();
        $dataSet->setTitle($lang->tr('Sent MB'))
            ->setData($reverseBytesOut)
            ->setConfigurationDataSet([
                "backgroundColor" => 'rgba(174, 198, 57, 0.79)',
                "borderColor" => 'rgba(174, 198, 57, 1)',
            ]);
        $this->addDataSet($dataSet);
    }
}