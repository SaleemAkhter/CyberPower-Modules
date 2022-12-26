<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Bandwidth\Pages;

use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Graphs\Line;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Graphs\Models\DataSet;
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;

class BandwidthGraph extends Line implements ClientArea
{

    protected $id = 'bandwidthGraph';
    protected $name = 'bandwidthGraph';

    public function initContent()
    {
        $this->updateChartScale('yAxes', [
            [
                'scaleLabel' => [
                    'display'     => true,
                    'labelString' => sl('lang')->tr('Bytes/s')
                ],
                'ticks'      => [
                    'callback' => 'mgBytesToSize'
                ],
            ]]);
        $this->updateChartOption('tooltips', [
            'callbacks' => [
                'label' => 'mgTooltipCallbackForNet'
            ]
        ]);

    }
    public function prepareAjaxData()
    {
        $labels = []; //labels on the bottom
        $dataSets   = [
            'incomingBytes'     => [],
            'outgoingBytes'    => [],
        ];
        $instance = (new InstanceFactory())->fromWhmcsParams();
        foreach ($instance->bandwidth()->bandwidth as $data => $bandwidth ){
            $labels[] = $data;
            $dataSets['incomingBytes'][]    =  $bandwidth->incoming_bytes;
            $dataSets['outgoingBytes'][]   = $bandwidth->outgoing_bytes;;
        }
        $this->setLabels($labels);
        //Labels
        $this->setLabels($labels);
        //incoming
        $lang    = sl('lang');
        $dataSet = new DataSet();
        $dataSet->setTitle($lang->tr('Bytes Received'))
            ->setData($dataSets['incomingBytes'])
            ->setConfigurationDataSet([
                "backgroundColor" => 'rgba(174, 198, 57, 0.79)',
                "borderColor"     => 'rgba(174, 198, 57, 1)',
            ]);
        $this->addDataSet($dataSet);
        //outgoing
        $dataSet = new DataSet();
        $dataSet->setTitle($lang->tr('Bytes Sent'))
            ->setData( $dataSets['outgoingBytes'])
            ->setConfigurationDataSet([
                "backgroundColor" => 'rgba(39, 133, 134, 0.91)',
                "borderColor"     => 'rgba(39, 133, 134, 1)',
            ]);
        $this->addDataSet($dataSet);
    }
}