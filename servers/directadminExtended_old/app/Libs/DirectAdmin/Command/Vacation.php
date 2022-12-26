<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Vacation extends AbstractCommand
{
    const CMD_EMAIL_VACATION        = 'CMD_API_EMAIL_VACATION';
    const CMD_EMAIL_VACATION_V2        = 'CMD_EMAIL_VACATION';
    const CMD_EMAIL_VACATION_MODIFY = 'CMD_API_EMAIL_VACATION_MODIFY';

    /**
     * get vacation email list
     *
     * @param Models\Command\Vacation $vacation
     * @return mixed
     */
    public function lists(Models\Command\Vacation $vacation)
    {
        $response = $this->curl->request(self::CMD_EMAIL_VACATION, [
            'domain' => $vacation->getDomain()
        ]);

        return $this->loadResponse(new Models\Command\Vacation(), $response);
    }

    /**
     * create vacation email
     *
     * @param Models\Command\Vacation $vacation
     * @return mixed
     */
    public function create(Models\Command\Vacation $vacation)
    {
        return $this->curl->request(self::CMD_EMAIL_VACATION, [
            'action'        => __FUNCTION__,
            'create'        => ucfirst(__FUNCTION__),
            'user'          => $vacation->getUser(),
            'domain'        => $vacation->getDomain(),
            'text'          => $vacation->getText(),
            'starttime'     => $vacation->getStartTime(),
            'startmonth'    => $vacation->getStartMonth(),
            'startday'      => $vacation->getStartDay(),
            'startyear'     => $vacation->getStartYear(),
            'endtime'       => $vacation->getEndTime(),
            'endmonth'      => $vacation->getEndMonth(),
            'endday'        => $vacation->getEndDay(),
            'endyear'       => $vacation->getEndYear()
        ], null, true);
    }

    /**
     * view vacation email
     *
     * @param Models\Command\Vacation $vacation
     * @return mixed
     */
    public function view(Models\Command\Vacation $vacation)
    {
        $response =  $this->curl->request(self::CMD_EMAIL_VACATION_MODIFY, [
            'domain' => $vacation->getDomain(),
            'user'   => $vacation->getUser()
        ]);

        return $this->loadResponse(new Models\Command\Vacation(), $response, __FUNCTION__);
    }

    /**
     * modify vacation email
     *
     * @param Models\Command\Vacation $vacation
     * @return mixed
     */
    public function modify(Models\Command\Vacation $vacation)
    {
        return $this->curl->request(self::CMD_EMAIL_VACATION, [
            'action'        => __FUNCTION__,
            'domain'        => $vacation->getDomain(),
            'user'          => $vacation->getUser(),
            'text'          => html_entity_decode($vacation->getText(), ENT_QUOTES, 'UTF-8'),
            'starttime'     => $vacation->getStartTime(),
            'startmonth'    => $vacation->getStartMonth(),
            'startday'      => $vacation->getStartDay(),
            'startyear'     => $vacation->getStartYear(),
            'endtime'       => $vacation->getEndTime(),
            'endmonth'      => $vacation->getEndMonth(),
            'endday'        => $vacation->getEndDay(),
            'endyear'       => $vacation->getEndYear()
        ], null, true);
    }

    /**
     * delete vacation email
     *
     * @param Models\Command\Vacation $vacation
     * @return mixed
     */
    public function delete(Models\Command\Vacation $vacation)
    {
        return $this->curl->request(self::CMD_EMAIL_VACATION, [
            'action'    => __FUNCTION__,
            'domain'    => $vacation->getDomain(),
            'select0'   => $vacation->getUser()
        ]);
    }

    public function deleteMany(array $deleteData)
    {
        $data = [
            'json'    => 'yes',
            'action' => 'delete',
            'domain' => null
        ];

        foreach($deleteData as $key => $value)
        {
            if($data['domain'] !== $key || empty($data['domain']))
            {
                $data['domain'] = $key;
                foreach($value as $elem => $each)
                {
                    $data['select'.$elem] = $each;
                }
                $this->curl->request(self::CMD_EMAIL_VACATION_V2, [], $data);
            }
        }
    }
}