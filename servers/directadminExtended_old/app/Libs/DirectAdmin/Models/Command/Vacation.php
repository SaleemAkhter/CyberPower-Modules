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

class Vacation extends AbstractModel implements ResponseLoad
{
    public $domain;
    public $user;
    public $starttime;
    public $startday;
    public $startmonth;
    public $startyear;
    public $endday;
    public $endmonth;
    public $endyear;
    public $endtime;
    protected $text;

    public function loadResponse($response, $function = null)
    {
        if ($function === 'view')
        {
            $this->addResponseElement(new self($response));

            return $this;
        }

        foreach ($response as $user => $dataStr)
        {
            parse_str($dataStr,$dataArray);
            $data = [
                'user'  => $user
            ];
            $data = array_merge($data, $dataArray);
            $this->addResponseElement(new self($data));
        }

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
     * @return Vacation
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Vacation
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return Vacation
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStarttime()
    {
        return $this->starttime;
    }

    /**
     * @param mixed $starttime
     * @return Vacation
     */
    public function setStarttime($starttime)
    {
        $this->starttime = $starttime;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStartday()
    {
        return $this->startday;
    }

    /**
     * @param mixed $startday
     * @return Vacation
     */
    public function setStartday($startday)
    {
        $this->startday = $startday;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStartmonth()
    {
        return $this->startmonth;
    }

    /**
     * @param mixed $startmonth
     * @return Vacation
     */
    public function setStartmonth($startmonth)
    {
        $this->startmonth = $startmonth;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStartyear()
    {
        return $this->startyear;
    }

    /**
     * @param mixed $startyear
     * @return Vacation
     */
    public function setStartyear($startyear)
    {
        $this->startyear = $startyear;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndday()
    {
        return $this->endday;
    }

    /**
     * @param mixed $endday
     * @return Vacation
     */
    public function setEndday($endday)
    {
        $this->endday = $endday;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndmonth()
    {
        return $this->endmonth;
    }

    /**
     * @param mixed $endmonth
     * @return Vacation
     */
    public function setEndmonth($endmonth)
    {
        $this->endmonth = $endmonth;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndyear()
    {
        return $this->endyear;
    }

    /**
     * @param mixed $endyear
     * @return Vacation
     */
    public function setEndyear($endyear)
    {
        $this->endyear = $endyear;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndtime()
    {
        return $this->endtime;
    }

    /**
     * @param mixed $endtime
     * @return Vacation
     */
    public function setEndtime($endtime)
    {
        $this->endtime = $endtime;
        return $this;
    }

}