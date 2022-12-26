<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 16:37
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Interfaces\ResponseLoad;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;

class Cron extends AbstractModel implements ResponseLoad
{
    public $id;
    protected $domain;
    public $minute;
    public $hour;
    public $day;
    public $month;
    public $week;
    public $command;

    public function loadResponse($response, $function = null)
    {
        foreach ($response as $id => $data)
        {
            $dataArray = explode(' ', $data);

            if($id == "MAILTO")
            {
                continue;
            }
            $self      = array_merge([
                'minute'    => $dataArray[0],
                'hour'      => $dataArray[1],
                'day'       => $dataArray[2],
                'month'     => $dataArray[3],
                'week'      => $dataArray[4],
                'command'   => $this->getFullCommand($dataArray)
            ], ['id' => $id]);
            $this->addResponseElement(new self($self));
        }

        return $this;
    }

    /**
     * @param array $dataArray
     * @return string
     */
    private function getFullCommand($dataArray = []){
        unset($dataArray[0]);
        unset($dataArray[1]);
        unset($dataArray[2]);
        unset($dataArray[3]);
        unset($dataArray[4]);

        return implode(' ', $dataArray);

    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Cron
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     * @return Cron
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * @param mixed $minute
     * @return Cron
     */
    public function setMinute($minute)
    {
        $this->minute = $minute;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @param mixed $hour
     * @return Cron
     */
    public function setHour($hour)
    {
        $this->hour = $hour;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     * @return Cron
     */
    public function setDay($day)
    {
        $this->day = $day;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param mixed $month
     * @return Cron
     */
    public function setMonth($month)
    {
        $this->month = $month;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeek()
    {
        return $this->week;
    }

    /**
     * @param mixed $week
     * @return Cron
     */
    public function setWeek($week)
    {
        $this->week = $week;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param mixed $command
     * @return Cron
     */
    public function setCommand($command)
    {
        $this->command = $command;
        return $this;
    }
}