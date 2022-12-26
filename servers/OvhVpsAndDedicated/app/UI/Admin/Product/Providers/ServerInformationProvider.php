<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Providers;

use Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\ServiceInformation\ServiceInformation;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\WhmcsParams;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\isAdmin;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Dedicated;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\IP\Repository as IPRepository;


/**
 * Description of Rebuild
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ServerInformationProvider extends BaseDataProvider implements ClientArea, AdminArea
{
    use WhmcsParamsApp;

    public function read()
    {

    }

    public function create()
    {
    }

    public function delete()
    {
    }

    public function update()
    {

    }

    public function getVpsInformation()
    {
        $vps         = (new Vps\Repository($this->getWhmcsEssentialParams()))->get();

        $model = $vps->model();

        $data = $model ? $model->toArray() : [];

        $this->assignPrimaryV4Address($this->getPrimaryIp($model->getName()), $data);
        $this->assignExtraData($data);

        if(!isAdmin())
        {
            $this->prepareForClientVps($data);
        }
        //product validation
        $fieldProvider = new FieldsProvider($this->getWhmcsParamByKey('pid'));
        $fields = $fieldProvider->getServiceInformation();
        if($fields){
            foreach ($data as $key => $value){
                if(!is_array($data[$key]) && !in_array($key, $fields)){
                    unset($data[$key]);
                }
            }
        }
        $data = ServiceInformation::convertInformation($data);

        return $data;
    }



    public function getInformation()
    {

        $server         = (new Dedicated\Repository($this->getAppParams(WhmcsParams::getEssentialsKeys())))->get();
        $data = $server->model()->toArray();

//        $data['backup'] = $server->getBackupInformation($serverModel->getName()); //BACKUPS


        if(!isAdmin())
        {
            $this->prepareForClientDedicated($data);
        }

        //product validation
        $fieldProvider = new FieldsProvider($this->getWhmcsParamByKey('pid'));
        $fields = $fieldProvider->getServiceInformation();
        if($fields){
            foreach ($data as $key => $value){
                if(!is_array($data[$key]) && !in_array($key, $fields)){
                    unset($data[$key]);
                }
            }
        }

        return ServiceInformation::convertDedicatedInformation($data);
    }

    private function prepareForClientDedicated(&$data)
    {
        unset($data['commercialRange']);
        unset($data['rack']);
        unset($data['name']);
        unset($data['datacenter']);
        unset($data['supportLevel']);

    }

    private function prepareForClientVps(&$data)
    {
        unset($data['cluster']);
        unset($data['zone']);
        unset($data['name']);
        unset($data['offerType']);
        unset($data['version']);
    }

    private function assignPrimaryV4Address($ip, &$data)
    {
        if($ip == null)
        {
            return;
        }
        $data['ip'] = $ip->getIpAddress();
        $data['ipReverse'] = $ip->getReverse();
    }

    private function assignExtraData(&$data)
    {
        $extraData = [
            'memoryLimit' => $data['memoryLimit'],
            'disk' =>  $data['model']['disk'] . " GB",
        ];

        unset($data['memoryLimit']);

        $data += $extraData;

    }

    public function getPrimaryIp($machineName)
    {
        $ipRepository = new IPRepository($machineName);
        $ips = $ipRepository->getAllToModel();;
        foreach ($ips as $ip)
        {
            if($ip->getType() == 'primary' && $ip->getVersion() == 'v4')
            {
                return $ip;
            }
        }
        return null;
    }
}
