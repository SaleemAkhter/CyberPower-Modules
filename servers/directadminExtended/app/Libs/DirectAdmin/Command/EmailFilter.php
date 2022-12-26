<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class EmailFilter extends AbstractCommand
{
    const CMD_EMAIL_FILTER = 'CMD_API_EMAIL_FILTER';
    const CMD_EMAIL_FILTER_V2 = 'CMD_EMAIL_FILTER';

    /**
     * get email filters list
     *
     * @param Models\Command\EmailFilter $emailFilter
     * @return mixed
     */
    public function lists(Models\Command\EmailFilter $emailFilter)
    {
        $response = $this->curl->request(self::CMD_EMAIL_FILTER, [
            'domain' => $emailFilter->getDomain()
        ]);

        return $this->loadResponse(new Models\Command\EmailFilter(), $response);
    }

    /**
     * flip adult filter on/off
     *
     * @param Models\Command\EmailFilter $emailFilter
     * @return mixed
     */
    public function adult(Models\Command\EmailFilter $emailFilter)
    {
        return $this->curl->request(self::CMD_EMAIL_FILTER, [
            'action'    => __FUNCTION__,
            'domain'    => $emailFilter->getDomain()
        ]);
    }

    /**
     * add spam filter
     *
     * @param Models\Command\EmailFilter $emailFilter
     * @return mixed
     */
    public function add(Models\Command\EmailFilter $emailFilter)
    {
        return $this->curl->request(self::CMD_EMAIL_FILTER, [
            'action'    => __FUNCTION__,
            'domain'    => $emailFilter->getDomain(),
            'type'      => $emailFilter->getType(),
            'value'     => $emailFilter->getValue()
        ]);
    }

    /**
     * set action
     *
     * @param Models\Command\EmailFilter $emailFilter
     * @return mixed
     */
    public function action(Models\Command\EmailFilter $emailFilter)
    {
        return $this->curl->request(self::CMD_EMAIL_FILTER, [
            'action'    => __FUNCTION__,
            'domain'    => $emailFilter->getDomain(),
            'value'     => $emailFilter->getValue()
        ]);
    }

    /**
     * delete email filter
     *
     * @param Models\Command\EmailFilter $emailFilter
     * @return mixed
     */
    public function delete(Models\Command\EmailFilter $emailFilter)
    {
        return $this->curl->request(self::CMD_EMAIL_FILTER, [
            'action'    => __FUNCTION__,
            'domain'    => $emailFilter->getDomain(),
            'select0'   => $emailFilter->getId()
        ]);
    }

    public function deleteMany(array $deleteData)
    {
        $data = [
            'json'    => 'yes',
            'action' => 'delete'
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
                $this->curl->request(self::CMD_EMAIL_FILTER_V2, [], $data);
            }
        }
    }
}