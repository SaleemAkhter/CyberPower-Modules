<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class ApacheHandler extends AbstractCommand
{
    const MULTIPLE     = 'multiple';
    const CMD_HANDLERS = 'CMD_API_HANDLERS';
    const CMD_HANDLERS_V2 = 'CMD_HANDLERS';

    /**
     * list apache handlers
     *
     * @param Models\Command\ApacheHandler $apacheHandler
     * @return mixed
     */
    public function lists(Models\Command\ApacheHandler $apacheHandler)
    {
        $response = $this->curl->request(self::CMD_HANDLERS, [
            'domain' => $apacheHandler->getDomain()
        ]);

        return $this->loadResponse(new Models\Command\ApacheHandler(), $response);
    }

    /**
     * add apache handler
     *
     * @param Models\Command\ApacheHandler $apacheHandler
     * @return mixed
     */
    public function add(Models\Command\ApacheHandler $apacheHandler)
    {
        return $this->curl->request(self::CMD_HANDLERS, [
            'action'    => __FUNCTION__,
            'domain'    => $apacheHandler->getDomain(),
            'name'      => $apacheHandler->getName(),
            'extension' => $apacheHandler->getExtension()
        ]);
    }

    /**
     * delete apache handler
     *
     * @param Models\Command\ApacheHandler $apacheHandler
     * @return mixed
     */
    public function delete(Models\Command\ApacheHandler $apacheHandler)
    {
        return $this->curl->request(self::CMD_HANDLERS, [
            'action'    => self::MULTIPLE,
            'button'    => 'type',
            'domain'    => $apacheHandler->getDomain(),
            'select0'   => $apacheHandler->getName()
        ]);
    }

    /**
     * delete apache handler extension
     *
     * @param Models\Command\ApacheHandler $apacheHandler
     * @return mixed
     */
    public function deleteExtension(Models\Command\ApacheHandler $apacheHandler)
    {
        return $this->curl->request(self::CMD_HANDLERS, [
            'action'    => self::MULTIPLE,
            'button'    => 'extension',
            'domain'    => $apacheHandler->getDomain(),
            'extension' => $apacheHandler->getExtension(),
            'select0'   => $apacheHandler->getName()
        ]);
    }

    /**
     * view domain handlers
     *
     * @param Models\Command\ApacheHandler $apacheHandler
     * @return mixed
     */
    public function view(Models\Command\ApacheHandler $apacheHandler)
    {
        $response = $this->curl->request(self::CMD_HANDLERS, [
            'domain' => $apacheHandler->getDomain()
        ]);

        return $this->loadResponse(new Models\Command\ApacheHandler(), $response, __FUNCTION__);
    }

    /**
     * view system handlers
     *
     * @param Models\Command\ApacheHandler $apacheHandler
     * @return mixed
     */
    public function system(Models\Command\ApacheHandler $apacheHandler)
    {
        $response = $this->curl->request(self::CMD_HANDLERS, [
            'action'    => __FUNCTION__,
            'domain'    => $apacheHandler->getDomain()
        ]);

        return $this->loadResponse(new Models\Command\ApacheHandler(), $response, __FUNCTION__);
    }

    public function deleteMany(array $deleteData)
    {
        $data = [
            'json'    => 'yes',
            'action' => 'multiple',
            'button' => 'type',
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
                $this->curl->request(self::CMD_HANDLERS_V2, [], $data);
            }
        }
    }
}