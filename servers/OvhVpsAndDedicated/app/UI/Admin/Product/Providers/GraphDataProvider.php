<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Providers;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Helpers\GraphFormatter;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\HandlerError\Exceptions\Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Dedicated\Repository;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\ModuleSettings\Model as ModuleSettingsModel;


/**
 * Class GraphDataProvider
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class GraphDataProvider extends BaseDataProvider implements ClientArea, AdminArea
{
    use Lang;

    public function read()
    {
        // TODO: Implement read() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function getData()
    {
        $dedicated = (new Repository())->get();

        $records = [];
        foreach ($this->getParamsToChart() as $params)
        {
            try
            {
                $record = $dedicated->mrtg($params);;
            }
            catch (\Exception $exception)
            {
                continue;
            }
            $records[] = $record;
        }
        $graphFormatter = new GraphFormatter();

        return $graphFormatter->format($records, $this->getParamsToChart(true));
    }


    private function getParamsToChart($lang = false)
    {
        $options = ModuleSettingsModel::select('value')->where('setting', 'dedicatedMRTG')->value('value');
        $options = \json_decode($options);
        $period = 'monthly';
        $scope = $options->graphScope;
        if($scope != null && in_array($scope, array_keys(GraphFormatter::getGraphAvailableValues())))
        {
            $period = $scope;
        }

        $this->loadLang();

        return [
            [
                'period' => $period,
                'type' => $lang ? $this->lang->absoluteTranslate('graphs', 'line', 'errors:download') : 'errors:download',
            ],
            [
                'period' => $period,
                'type' => $lang ? $this->lang->absoluteTranslate('graphs', 'line', 'errors:upload') : 'errors:upload',
            ],
            [
                'period' => $period,
                'type' => $lang ? $this->lang->absoluteTranslate('graphs', 'line', 'packets:download') : 'packets:download',
            ],
            [
                'period' => $period,
                'type' => $lang ? $this->lang->absoluteTranslate('graphs', 'line', 'packets:upload') : 'packets:upload',
            ],
            [
                'period' => $period,
                'type' => $lang ? $this->lang->absoluteTranslate('graphs', 'line', 'traffic:download') : 'traffic:download',
            ],
            [
                'period' => $period,
                'type' =>  $lang ? $this->lang->absoluteTranslate('graphs', 'line', 'traffic:upload') : 'traffic:upload',
            ],
        ];
    }
}

