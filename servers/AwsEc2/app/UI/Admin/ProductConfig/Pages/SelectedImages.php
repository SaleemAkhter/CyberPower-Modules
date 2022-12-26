<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Pages;

use ModulesGarden\Servers\AwsEc2\App\Models\AvailableImages\Model;
use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Buttons\AddAmiManually;
use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Buttons\EditAmi;
use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Buttons\DeleteAmi;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\RawDataTable\RawDataTable;

class SelectedImages extends RawDataTable implements AdminArea
{
    protected $id = 'selectedImages';
    protected $name = 'selectedImages';
    protected $title = 'selectedImagesTitle';

    protected $actionIdColumnName = 'image_id';

    public function initContent()
    {
        $this->addColumn((new Column('image_id'))->setSearchable(true));
        $this->addColumn((new Column('description'))->setSearchable(true));

        $this->addButton(AddAmiManually::class);
        $this->addActionButton(EditAmi::class);
        $this->addActionButton(DeleteAmi::class);
    }

    public function loadData()
    {
        $productId = (int)$this->getRequestValue('id', 0);
        $imagesModel = new Model();
        $dataQuery = $imagesModel->query()->getQuery()->where('product_id', $productId);

        $dataProvieder = new QueryDataProvider();
        $dataProvieder->setData($dataQuery);
        $this->setDataProvider($dataProvieder);
    }
}
