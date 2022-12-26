<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Connection\Curl;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Interfaces\ResponseLoad;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;

class AbstractCommand
{
    /**
     * @var Curl
     */
    protected $curl;

    /**
     * set connection - curl
     *
     * @param $params
     * @param $userMode
     */
    public function setConnection($params, $userMode,$resellerMode='',$runAsAdmin='')
    {
        if($resellerMode){
            $params['runAsAdmin']=$runAsAdmin;
            $curlModel=Helper\Models\CurlModel::prepareReseller($params);
        }else{
            $curlModel  = $userMode ? Helper\Models\CurlModel::prepareUser($params) : Helper\Models\CurlModel::prepareAdmin($params);
        }
        $this->curl = new Curl($curlModel);
    }

    /**
     * load response to given model if implements proper interface else return raw response
     *
     * @param AbstractModel $model
     * @param $response
     * @param null $function
     * @return mixed
     */
    public function loadResponse(AbstractModel $model, $response, $function = null)
    {
        if($model instanceof ResponseLoad)
        {
            return $model->loadResponse($response, $function);
        }

        return $response;
    }
}
