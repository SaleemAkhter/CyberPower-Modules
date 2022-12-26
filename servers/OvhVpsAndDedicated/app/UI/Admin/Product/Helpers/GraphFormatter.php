<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Helpers;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ServiceLocator;

/**
 * Class GraphFormatter
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class GraphFormatter
{
    public function format($records, $params)
    {
        $colors = $this->getColors();
        $result = [];
        $i = 0;
        foreach ($records as $record)
        {
            $response = $this->formatForChart($record, $params[$i], $colors[$i]);
            $result[] = $response;
            $i++;
        }

        return $result;
    }

    public function formatForChart($result, $params, $config)
    {
        $out = [];
        foreach ($result as $period)
        {
            $out['labels'][] = date("Y-m-d", $period['timestamp']);
            $out['data'][] = $period['value']['value'];
        }
        $out['config'] =  $config;
        $out['labelDescription'] = $params['type'];

        return $out;
    }

    private function getColors()
    {
        return [
            [
                'backgroundColor' => "rgba(174, 198, 57, 0.7)",
                'borderColor' => "rgba(174, 198, 57, 1)",
            ],
            [
                'backgroundColor' => "rgba(5, 175, 194, 0.7)",
                'borderColor' => "rgba(5, 175, 194, 1)",
            ],
            [
                'backgroundColor' => "rgba(11, 59, 249, 0.7)",
                'borderColor' => "rgba(11, 59, 249, 1)",
            ],
            [
                'backgroundColor' => "rgba(146, 251, 81, 0.7)",
                'borderColor' => "rgba(146, 251, 81, 1)",
            ],
            [
                'backgroundColor' => "rgba(251, 81, 81, 0.7)",
                'borderColor' => "rgba(251, 81, 81, 1)",
            ],
            [
                'backgroundColor' => "rgba(175, 116, 251, 0.7)",
                'borderColor' => "rgba(175, 116, 251, 1)",
            ],
            [
                'backgroundColor' => "rgba(111, 6, 249, 0.7)",
                'borderColor' => "rgba(111, 6, 249, 1)",
            ],

        ];
    }

    public static function getGraphAvailableValues()
    {
        $lang = ServiceLocator::call('lang');
        return [
            'daily' => $lang->translate('graphScopeValue','day'),
            'weekly' => $lang->translate('graphScopeValue','week'),
            'monthly' => $lang->translate('graphScopeValue','month')
        ];
    }
}