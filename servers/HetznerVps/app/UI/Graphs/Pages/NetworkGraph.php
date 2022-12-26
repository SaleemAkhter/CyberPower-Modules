<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Graphs\Pages;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Graphs\Line;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Graphs\Models\DataSet;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

class NetworkGraph extends Line implements ClientArea, AdminArea
{

    use GraphData;

    protected $id = 'networkGraph';
    protected $name = 'networkGraph';
    protected $title = 'networkGraph';

    public function initContent()
    {
        $this->selectScope();
        $this->addSettingField($this->selectScope);
        $this->updateChartScale('yAxes', [
            [
                'scaleLabel' => [
                    'display'     => true,
                    'labelString' => 'Bytes/s'
                ],
                //                'ticks'      => [
                //                    'callback' => 'mgBytesToSize'
                //                ],
            ]]);

//        $this->updateChartOption('tooltips', [
//            'callbacks' => [
//                'label' => 'mgTooltipCallbackForNet'
//            ]
//        ]);
//        $this->addTitleButton(new GraphEditButton());
    }

    public function prepareAjaxData()
    {
        $rrData = $this->getApiData()->getResponse();
        $netIn  = $rrData['metrics']->{'time_series'}->{'network.0.bandwidth.in'}->{'values'};
        $netOut = $rrData['metrics']->{'time_series'}->{'network.0.bandwidth.out'}->{'values'};

        $labels   = [];
        $dataSets = [
            'netin'  => [],
            'netout' => [],
        ];
        $this->dateFormat();
        foreach ($netIn as $rrd)
        {
            $labels[]            = date($this->dateFormat, $rrd['0']);
            $dataSets['netin'][] = round((float)$rrd['1'], 2);
        }
        foreach ($netOut as $rrd)
        {
            $dataSets['netout'][] = round((float)$rrd['1'], 2);
        }

        //Labels
        $this->setLabels($labels);
        //Net In
        $lang    = sl('lang');
        $dataSet = new DataSet();
        $dataSet->setTitle($lang->absoluteT('Net In'))
            ->setData($dataSets['netin'])
            ->setConfigurationDataSet([
                "backgroundColor" => 'rgba(174, 198, 57, 0.79)',
                "borderColor"     => 'rgba(174, 198, 57, 1)',
            ]);
        $this->addDataSet($dataSet);
        //Net Out
        $dataSet = new DataSet();
        $dataSet->setTitle($lang->absoluteT('Net Out'))
            ->setData($dataSets['netout'])
            ->setConfigurationDataSet([
                "backgroundColor" => 'rgba(39, 133, 134, 0.91)',
                "borderColor"     => 'rgba(39, 133, 134, 1)',
            ]);
        $this->addDataSet($dataSet);
    }
}