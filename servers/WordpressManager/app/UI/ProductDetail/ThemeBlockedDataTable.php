<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use \ModulesGarden\WordpressManager\Core\Helper;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\RawDataTable\RawDataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use \ModulesGarden\WordpressManager\App\Models\ThemeBlocked;

class ThemeBlockedDataTable extends RawDataTable  implements AdminArea
{
    protected $id    = 'mg-themes-blocked';
    protected $name  = 'mg-themes-blocked-name';
    protected $title = 'mg-themes-blocked';

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
        $this->addMassActionButton(new ThemeBlockedDeleteMassButton('themeBlockedDeleteMassButton'));
        $this->addActionButton(new ThemeBlockedDeleteButton('themeBlockedDeleteButton'));
    }


    protected function loadData()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request  = Helper\sl('request');
        $p        = (new Product())->getTable();
        $query    = (new ThemeBlocked)
                ->query()
                ->getQuery()
                ->where("{$pb}.product_id", $request->get('id'));
        $dataProv = new QueryDataProvider();
        $dataProv->setDefaultSorting("{$pb}.name", 'asc');
        $dataProv->setData($query);
        $this->setDataProvider($dataProv);
        
    }
}
