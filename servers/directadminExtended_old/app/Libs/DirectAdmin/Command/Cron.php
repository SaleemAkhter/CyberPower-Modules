<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Cron extends AbstractCommand
{
    const CMD_CRON  = 'CMD_API_CRON_JOBS';

    /**
     * get cron jobs list
     *
     * @param Models\Command\Cron $cron
     * @return mixed
     */
    public function lists(Models\Command\Cron $cron)
    {
        $response =  $this->curl->request(self::CMD_CRON, [
            'domain' => $cron->getDomain()
        ]);

        return $this->loadResponse(new Models\Command\Cron(), $response);
    }

    /**
     * create cron job
     *
     * @param Models\Command\Cron $cron
     * @return mixed
     */
    public function create(Models\Command\Cron $cron)
    {
        return $this->curl->request(self::CMD_CRON, [
            'action'        => __FUNCTION__,
            'minute'        => $cron->getMinute(),
            'hour'          => $cron->getHour(),
            'dayofmonth'    => $cron->getDay(),
            'month'         => $cron->getMonth(),
            'dayofweek'     => $cron->getWeek(),
            'command'       => $cron->getCommand()
        ]);
    }

    /**
     * delete cron job
     *
     * @param Models\Command\Cron $cron
     * @return mixed
     */
    public function delete(Models\Command\Cron $cron)
    {
        return $this->curl->request(self::CMD_CRON, [
            'action'    => __FUNCTION__,
            'select0'   => $cron->getId()
        ]);
    }
    public function massDelete(Models\Command\Cron $cron)
    {
        $data = $cron->explodeMassOptions('id');
        $data['action']  = 'delete';

        return $this->curl->request(self::CMD_CRON, $data);
    }


    public function save(Models\Command\Cron $cron)
    {

        return $this->curl->request(self::CMD_CRON, [
            'save'        => __FUNCTION__,
            'id'            => $cron->getId(),
            'minute'        => $cron->getMinute(),
            'hour'          => $cron->getHour(),
            'dayofmonth'    => $cron->getDay(),
            'month'         => $cron->getMonth(),
            'dayofweek'     => $cron->getWeek(),
            'command'       => $cron->getCommand()
        ]);

    }
}