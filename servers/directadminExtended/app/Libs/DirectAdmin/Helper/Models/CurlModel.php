<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 14:44
 */
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Helper\Models;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class CurlModel
{
    /**
     * Prepare basic curl model
     *
     * @param $params
     * @return Models\Connection\Curl
     */
    public static function prepareBasic($params)
    {
        $model = (new Models\Connection\Curl())
            ->setHostname($params['serverhostname'])
            ->setIp($params['serverip'])
            ->setPort(!empty($params['serverport']) ? $params['serverport'] : 2222)
            ->setSsl($params['serversecure'] == 'on' ? true : false);

        return $model;
    }

    /**
     * Prepare user curl model for connection
     *
     * @param $params
     * @return Models\Connection\Curl
     */
    public static function prepareUser($params)
    {
        $model = self::prepareBasic($params);
        $model->setUsername($params['username'])
            ->setPassword($params['password']);

        return $model;
    }

    /**
     * Prepare admin curl model for connection
     *
     * @param $params
     * @return Models\Connection\Curl
     */
    public static function prepareAdmin($params)
    {
        $model = self::prepareBasic($params);
        $model->setUsername($params['serverusername'])
            ->setPassword($params['serverpassword']);

        return $model;
    }
    /**
     * Prepare admin curl model for connection
     *
     * @param $params
     * @return Models\Connection\Curl
     */
    public static function prepareReseller($params)
    {
        $model = self::prepareBasic($params);
        $model->setUsername($params['username'])
            ->setPassword($params['serverpassword'])
            ->setRunAsAdmin($params['runAsAdmin']);
        return $model;
    }
}
