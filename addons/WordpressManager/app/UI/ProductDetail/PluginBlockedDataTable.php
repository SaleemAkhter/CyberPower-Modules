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

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders as providers;
use \ModulesGarden\WordpressManager\Core\Helper;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\RawDataTable\RawDataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use \ModulesGarden\WordpressManager\App\Models\PluginBlocked;


/**
 * Description of PluginInstalled
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PluginBlockedDataTable extends RawDataTable  implements AdminArea
{
    protected $id    = 'mg-plugins-blocked';
    protected $name  = 'mg-plugins-blocked-name';
    protected $title = 'mg-plugins-blocked';

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
        // delete, delete mass
        $this->addMassActionButton(new PluginBlockedDeleteMassButton('pluginBlockedDeleteMassButton'));
        $this->addActionButton(new PluginBlockedDeleteButton('pluginBlockedDeleteButton'));
    }


    protected function loadData()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request  = Helper\sl('request');
        $p        = (new Product())->getTable();
        $query    = (new PluginBlocked)
                ->query()
                ->getQuery()
                ->where("{$pb}.product_id", $request->get('id'));
        $dataProv = new QueryDataProvider();
        $dataProv->setDefaultSorting("{$pb}.name", 'asc');
        $dataProv->setData($query);
        $this->setDataProvider($dataProv);
        
    }
}
