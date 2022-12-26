<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Fields;

use ModulesGarden\Servers\AwsEc2\App\Models\AvailableImages\Model;
use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\AjaxFields\Select;

class Amis extends Select implements AdminArea
{
    protected $id = 'ami';
    protected $name = 'ami';
    protected $title = 'ami';

    public function prepareAjaxData()
    {
        $this->setAvailableValues($this->loadAvailableAmis());

        $this->setSelectedValue($this->getSelectedAmi());
    }

    protected function loadAvailableAmis()
    {
        $productId = (int)$this->getRequestValue('id', 0);

        $amis = [];
        $imagesModel = new Model();
        $images = $imagesModel->where('product_id', $productId)->get();
        if (!$images)
        {
            return [];
        }

        foreach ($images->all() as $image)
        {
            $amis[] = [
                'key' => $image->name ? : $image->image_id,
                'value' => $image->description
            ];
        }

        usort($amis, [$this, 'compsareAmis']);

        return $amis;
    }

    public function compsareAmis($ami1, $ami2)
    {
        return strnatcmp(strtolower($ami1['value']), strtolower($ami2['value']));
    }

    protected function getSelectedAmi()
    {
        $productId = $this->getRequestValue('id');

        $settingRepo = new Repository();
        $productSettings = $settingRepo->getProductSettings($productId);

        return $productSettings['ami'];
    }
}
