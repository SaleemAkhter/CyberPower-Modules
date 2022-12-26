<?php

namespace ModulesGarden\WordpressManager\App\Http\Admin;

use ModulesGarden\WordpressManager\Core\Http\AbstractController;
use ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\UI\Admin\PluginPackages\PluginPackageDataTable;
use ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Pages\Plugin\CustomPluginsPage;
use ModulesGarden\WordpressManager as main;

class Plugins extends AbstractController
{
    public function pluginPackages()
    {
        return Helper\view()->addElement(PluginPackageDataTable::class);
    }
    
    public function customs()
    {
        return Helper\view()->addElement(CustomPluginsPage::class);
    }

    public function create(){
        return $this->update();
    }

    public function update()
    {
        //if no plugin package exists redirect to the create package page
        $editId = $this->getRequestValue('id', false);
        if ($editId)
        {
            $dataModel = main\Core\DependencyInjection\DependencyInjection::call(main\App\Models\PluginPackage::class);
            $dbData = $dataModel->where('id', $editId)->first();
            if ($dbData === null)
            {
                return main\Core\Helper\redirect('Plugins', 'update');
            }
        }

        return Helper\view()->setCustomJsCode()->addElement(main\App\UI\Admin\PluginPackageItems\ItemsContainer::class);
    }
}
