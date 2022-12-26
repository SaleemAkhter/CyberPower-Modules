<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Pages\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Helpers\GraphFormatter;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Providers\GraphDataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\RequestObjectHandler;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Graphs\Line;

/**
 * Class GraphPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class GraphPage extends Line implements AdminArea, ClientArea
{
    use RequestObjectHandler;

    protected $id    = 'lineGraph1';
    protected $name  = 'lineGraph1';

    protected $graphSettingsEnabled = true;
    protected $graphSettingsKey = 'dedicatedMRTG';

    public function initContent()
    {
        $selectScope = new Select('graphScope');
        $selectScope->setavailableValues(GraphFormatter::getGraphAvailableValues());

        $this->addSettingField($selectScope);
    }

    public function prepareAjaxData()
    {
        $this->setChartTypeToLine();

        $provider = new GraphDataProvider();
        $records = $provider->getData();

        foreach ($records as $data)
        {
            $this->setLabels($data['labels']);

            $dataSet = new \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Graphs\Models\DataSet();
            $dataSet->setTitle($data['labelDescription'])
                ->setData($data['data'])
                ->setConfigurationDataSet($data['config']);

            $this->addDataSet($dataSet);
        }

        if(empty($records))
        {
            $this->setLabels(['']);
            $dataSet = new \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Graphs\Models\DataSet();
            $dataSet->setTitle('')
                ->setData([0])
                ->setConfigurationDataSet([]);

            $this->addDataSet($dataSet);

        }
    }
}
