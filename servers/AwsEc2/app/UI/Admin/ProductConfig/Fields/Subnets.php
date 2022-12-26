<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Fields;


use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\AjaxFields\Select;

class Subnets extends Select implements AdminArea
{

    protected $id   = 'subnet';
    protected $name = 'subnet';

    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-ajax-select';

    public function initContent()
    {
        parent::initContent();
    }

    public function prepareAjaxData()
    {
        $this->setAvailableValues($this->loadAvailableSubnets());
        $this->setSelectedValue($this->getSelectedSubnet());
    }

    private function getSelectedSubnet()
    {
        $productId = $this->getRequestValue('id');

        $settingRepo = new Repository();
        $productSettings = $settingRepo->getProductSettings($productId);
        $subnet = $productSettings['subnet'];

        if(array_reduce($this->availableValues, function($result, $item) use ($subnet){
                return $result || ($item['key'] == $subnet);
            }, false))
            return $subnet;
        else
            return 'default';
    }

    private function loadAvailableSubnets()
    {
        $productId = $this->getRequestValue('id');

        $awsClient = new ClientWrapper($productId, null, $this->getRequestValue('mgpci')['region']);
        $result = $awsClient->describeSubnets([])->get('Subnets');
        $namesIds = [];
        $namesIds[] = ['key' => 'default', 'value' => 'default'];
        foreach ($result as $subnet) {
            if($subnet['State'] != 'available')
                continue;
            $name = '';
            foreach ($subnet['Tags'] as $key => $value) {
                if($value['Key'] == 'Name' && $value['Value'])
                    $name = $value['Value'];
            }
            $namesIds[] = [
                'key' => $subnet['SubnetId'],
                'value' => $name ? $subnet['SubnetId'] . " (" . $name . ")" : $subnet['SubnetId']
            ];
        }

        return $namesIds;

    }
}