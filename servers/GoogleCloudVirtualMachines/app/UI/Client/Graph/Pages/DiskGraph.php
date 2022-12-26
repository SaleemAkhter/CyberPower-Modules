<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Graph\Pages;


use Google\ApiCore\PagedListResponse;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\InstanceFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Graphs\Models\DataSet;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ColorHandler;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;

class DiskGraph extends AbstractGoogleGraph implements ClientArea
{
    use ColorHandler;

    protected $id = 'diskGraph';
    protected $name = 'diskGraph';
    protected $graphSettingsEnabled = false;

    protected $dataSets = [];
    protected $labels = [];


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
                'label' => 'mgTooltipDisk'
            ]
        ]);
    }

    public function prepareAjaxData()
    {
        $instance = (new InstanceFactory())->fromParams();

        $diskReadFiler = "metric.type = \"compute.googleapis.com/instance/disk/write_bytes_count\" AND resource.labels.instance_id = \"" . $instance->getId() . "\"";
        $diskWriteFilter = "metric.type = \"compute.googleapis.com/instance/disk/read_bytes_count\" AND resource.labels.instance_id = \"" . $instance->getId() . "\"";

        $readResults = $this->listTimeSeries($diskReadFiler);
        $writeResults = $this->listTimeSeries($diskWriteFilter);

        $this->getResponseToArray($writeResults, $readResults);
        $this->getLabelsForChart();
        $this->setLabels($this->labels);

        foreach ($this->dataSets as $diskKey => $diskSet) {
            foreach ($diskSet as $dataKey => $diskDataSet) {
                $reversedDiskSets = array_reverse($diskDataSet);
                $color = $this->getNextColor();
                $title = $diskKey . ' ' . $dataKey;

                $graphDataSet = new DataSet();
                $graphDataSet->setTitle($title)
                    ->setData($reversedDiskSets)
                    ->setConfigurationDataSet([
                        "backgroundColor" => 'rgba(' . $color->getRed() . ', ' . $color->getGreen() . ', ' . $color->getBlue() . ' , 0.79)',
                        "borderColor" => 'rgba(' . $color->getRed() . ', ' . $color->getGreen() . ', ' . $color->getBlue() . ' , 1)'
                    ]);
                $this->addDataSet($graphDataSet);
            }
        }
    }

    private function getResponseToArray(PagedListResponse $writeResults, PagedListResponse $readResults): void
    {
        foreach ($writeResults->iterateAllElements() as $timeSeries) {
            foreach ($timeSeries->getPoints() as $point) {
                $this->dataSets[$timeSeries->getMetric()->getLabels()['device_name']]['write'][] = $point->getValue()->getDoubleValue()/1000000;
            }
            $entries = count($this->dataSets[$timeSeries->getMetric()->getLabels()['device_name']]['write']);

            while ($entries <= 23){
                $this->dataSets[$timeSeries->getMetric()->getLabels()['device_name']]['write'][] = 0;
                $entries++;
            }
        }

        foreach ($readResults->iterateAllElements() as $timeSeries) {
            foreach ($timeSeries->getPoints() as $point) {
                $this->dataSets[$timeSeries->getMetric()->getLabels()['device_name']]['read'][] = $point->getValue()->getDoubleValue()/1000000;
            }
            $entries = count($this->dataSets[$timeSeries->getMetric()->getLabels()['device_name']]['read']);

            while ($entries <= 23){
                $this->dataSets[$timeSeries->getMetric()->getLabels()['device_name']]['read'][] = 0;
                $entries++;
            }
        }
    }

    private function getLabelsForChart(): void
    {
        $date = new \DateTime();

        for ($i=0;$i<=23;$i++) {
            $this->labels[] = date('H:i', $date->getTimestamp());
            $date->modify('- 1hours');
        }

        $this->labels = array_reverse($this->labels);
    }
}