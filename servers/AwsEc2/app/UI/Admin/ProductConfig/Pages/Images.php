<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Pages;

use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\TabsWidget\TabsWidget;

class Images extends TabsWidget implements AdminArea
{
    protected $id = 'filterImages';
    protected $name = 'filterImages';
    protected $title = 'filterImagesTitle';

    public function initContent()
    {
        $this->addElement(SearchImages::class)
            ->addElement(SelectedImages::class);
    }
}
