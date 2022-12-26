<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Feb 5, 2018)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\UI\Admin\ThemePackageItems\Pages;

use ModulesGarden\WordpressManager\App\Models\ThemePackageItem;
use ModulesGarden\WordpressManager\App\UI\Admin\ThemePackageItems\Buttons\ItemDeleteButton;
use ModulesGarden\WordpressManager\App\UI\Admin\ThemePackageItems\Buttons\ItemDeleteMassButton;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders as providers;
use \ModulesGarden\WordpressManager\Core\Helper;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\RawDataTable\RawDataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider;
use \ModulesGarden\WordpressManager\App\Models\PluginPackageItem;
use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
/**
 * Description of PluginInstalled
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class ItemsDataTable extends RawDataTable  implements AdminArea
{
    use RequestObjectHandler;
    protected $id    = 'themeItems';
    protected $name  = 'themeItemsName';
    protected $title = 'themeItemsTitle';

    public function isRawTitle()
    {
        return false;
    }
    protected function loadHtml()
    {
        $this->addColumn((new Column('name'))->setSearchable(true, Column::TYPE_STRING)->setOrderable(DataProvider::SORT_ASC))
             ->addColumn((new Column('slug'))->setSearchable(true, Column::TYPE_STRING)->setOrderable());
    }

    public function initContent()
    {
        //delete, delete mass
        $this->addMassActionButton(new ItemDeleteMassButton('itemDeleteMassButton'));
        $this->addActionButton(new ItemDeleteButton('itemDeleteButton'));
    }


    protected function loadData()
    {
        $this->loadRequestObj();
        $pi = (new ThemePackageItem())->getTable();
        $query    = (new ThemePackageItem())
                ->query()
                ->getQuery()
                ->where("{$pi}.theme_package_id", $this->getRequestValue('id',0));
        $dataProv = new QueryDataProvider();
        $dataProv->setDefaultSorting("{$pi}.name", 'asc');
        $dataProv->setData($query);
        $this->setDataProvider($dataProv);
        
    }
}
