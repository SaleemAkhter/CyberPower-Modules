<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Fields;


use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\AjaxFields\Select;

class Region extends Select implements AdminArea
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
        $this->setAvailableValues($this->loadRegions());
        $this->setSelectedValue($this->getSelectedRegion());
    }

    private function getSelectedRegion()
    {
        $productId = $this->getRequestValue('id');

        $settingRepo = new Repository();
        $productSettings = $settingRepo->getProductSettings($productId);
        return $productSettings['region'];
    }

    private function loadRegions()
    {
        $productId = $this->getRequestValue('id');

        $awsClient = new ClientWrapper($productId, null);
        $result = $awsClient->getRegions();
        $namesIds = [];

        foreach ($result as $region) {
            $namesIds[] = [
                'key' => $region['RegionName'],
                'value' => $region['RegionName'],
            ];
        }

        return $namesIds;

    }
}