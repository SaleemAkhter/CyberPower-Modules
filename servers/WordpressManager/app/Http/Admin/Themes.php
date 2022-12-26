<?php

namespace ModulesGarden\WordpressManager\App\Http\Admin;

use ModulesGarden\WordpressManager\Core\Http\AbstractController;
use ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\UI\Admin\ThemePackages\Pages\ThemePackageDataTable;
use ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Pages\Theme\CustomThemesPage;
use ModulesGarden\WordpressManager as main;

class Themes extends AbstractController
{

    public function themePackages()
    {
        return Helper\view()->addElement(ThemePackageDataTable::class);
    }

    public function customs()
    {
        return Helper\view()->addElement(CustomThemesPage::class);
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
            $dataModel = main\Core\DependencyInjection\DependencyInjection::call(main\App\Models\ThemePackage::class);
            $dbData = $dataModel->where('id', $editId)->first();
            if ($dbData === null)
            {
                return main\Core\Helper\redirect('Themes', 'update');
            }
        }

        return Helper\view()->setCustomJsCode()->addElement(main\App\UI\Admin\ThemePackageItems\Sections\ItemsContainer::class);
    }

}
