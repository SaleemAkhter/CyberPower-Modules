<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Graphs\Pages;

use LKDev\HetznerCloud\APIException;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\Core\Models\ModuleSettings\Model;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Select;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

trait GraphData
{

    protected $params;
    protected $timeframe = "week";
    protected $selectScope;
    protected $dateFormat;

    public function chartData()
    {
        $request = [
            "timeframe" => $this->timeframe,
            "cf"        => "MAX",
        ];
        return $this->vm()->rrdData($request);
    }

    public function dateFormat()
    {
//        $this->dateFormat = in_array($this->timeframe, ['hour', 'day']) ? "H:i:s" : "Y-m-d";
        $this->dateFormat = "m/d-H:i:s";
//        $this->dateFormat = "Y-m-d";
    }


    /**
     * @param int $start
     * Start of period, current_time - passed_vaule in seconds,<br>
     * ex. <b>3600</b> means 1h ago<br>
     * max is <b>2 592 000</b> (30days)
     * @param int $end
     * End of period, current_time - passed_vaule in seconds,<br>
     * ex. <b>0</b> means now
     * @param null $step
     * Resolution of results in seconds,<br>
     * limit the number of samples returned is 500<br>
     * 3600 = 1h, 10800 = 3h, 21600 = 6h, 28800 = 8h, 43200 = 12h, 86400 = 1day
     * @return \LKDev\HetznerCloud\APIResponse|string
     */
    public function getApiData($start = 86400, $end = 0, $step = 480)
    {
        $time     = time();
        $type     = substr($this->name, 0, strpos($this->name, strpbrk($this->name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ')));
        $serverId = $this->getWhmcsParams()['customfields']['serverID'];
        $api      = new Api($this->getWhmcsParams());

        if ($start < 0)
        {
            return 'wrong value for *start*, must be positive number';
        }

        try
        {
            return $api->server($serverId)->metrics($type, date('c', $time - $start), date('c', $time - $end), $step);
        }
        catch (APIException $ex)
        {
            return $ex->getMessage();
        }
    }

    protected function selectScope()
    {
//        $this->selectScope = new Select('timeframe');
//        $this->selectScope->setAvailableValues($this->chartOptions()
//            [
//            'hour' => 'Hour',
//            'day' => 'Day',
//            'week' => 'Week',
//            'year' => 'Year',
//        ]
//        );
//        $this->selectScope->setDefaultValue('Day');
    }

    protected function chartOptions()
    {
        $registrationDate = new \DateTime($this->getWhmcsParamByKey('model')->registrationDate->format("Y-m-d"));
        $options          = ['minute' => sl("lang")->absoluteT("Minute")];
        $timeNow          = new \DateTime();
        $dDiff            = $registrationDate->diff($timeNow);
        if ($dDiff->i >= 15 || $dDiff->d >= 1)
        {
            $options['minute'] = sl("lang")->absoluteT("15 Minutes");
        }
        if ($dDiff->h >= 1 || $dDiff->d >= 1)
        {
            $options['hour'] = sl("lang")->absoluteT("1 Hour");
        }
        if ($dDiff->h >= 12 || $dDiff->d >= 1)
        {
            $options['hours'] = sl("lang")->absoluteT("12 Hours");
        }
        if ($dDiff->days >= 1)
        {
            $options['day'] = sl("lang")->absoluteT("1 Day");
        }
        if ($dDiff->days >= 7)
        {
            $options['week'] = sl("lang")->absoluteT("1 Week");
        }
        if ($dDiff->days >= 30)
        {
            $options['month'] = sl("lang")->absoluteT("1 Month");
        }
        return $options;
    }

    protected function loadSettings()
    {
        $this->configChartsSettings = json_decode(Model::where('setting', $this->graphSettingsKey)->first()->value);

        if ($this->configChartsSettings)
        {
            $this->setGraphFilterInfo(null, $this->configChartsSettings->start, $this->configChartsSettings->end);
        }

        return $this;
    }
}
