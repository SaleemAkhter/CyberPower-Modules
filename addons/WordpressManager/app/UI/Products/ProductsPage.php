<?php

namespace ModulesGarden\WordpressManager\App\UI\Products;

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders as providers;
use \ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Models\ProductSetting;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonRedirect;
use \ModulesGarden\WordpressManager\Core\Helper;

/**
 * Doe labels datatable controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ProductsPage extends DataTable implements AdminArea
{
    protected $id    = 'labelscont';
    protected $name  = 'labelscont';
    protected $title = null;

    protected function loadHtml()
    {
        $p = (new main\Core\Models\Whmcs\Product)->getTable();
        $this->addColumn((new Column('id', $p))->setSearchable(true, 'int')->setOrderable('DESC'))
                ->addColumn((new Column('name'))->setSearchable(true, 'string'))
                ->addColumn((new Column('servertype'))->setSearchable(true, 'string'))
                ->addColumn((new Column('status')));
    }

    public function initContent()
    {
        //Mass Actions
        $this->addMassActionButton(new Buttons\MassEnableButton);
        $rd      = new ButtonRedirect;
        $request = Helper\sl('request');
        $baseUrl = 'addonmodules.php?module=WordpressManager&mg-page=products&mg-action=detail';
        $rd->setRawUrl($baseUrl)
                ->setRedirectParams(['id' => ':id']);
        $rd->setIcon('lu-icon-in-button lu-zmdi lu-zmdi-edit');
        $this->addActionButton($rd);
    }

    public function customColumnHtmlStatus()
    {
        return (new Buttons\StatusSwitch)->getHtml();
    }

    public function replaceFieldPid($key, $row)
    {
        return sprintf('<a href="configproducts.php?action=edit&id=%d">%s</a>', $row->id, $row->id);
    }

    public function replaceFieldName($key, $row)
    {
        return sprintf('<a href="configproducts.php?action=edit&id=%d">%s</a>', $row->id, $row->name);
    }

    public function replaceFieldServertype($key, $row)
    {
        if ($row->servertype)
        {
            return sprintf('%s (%s)', Helper\sl('lang')->T($row->type), ucfirst($row->servertype));
        }
        return $row->type;
    }

    protected function loadData()
    {
        $p        = with(new main\Core\Models\Whmcs\Product)->getTable();
        $s        = with(new ProductSetting)->getTable();
        $query    = with(new main\Core\Models\Whmcs\Product)
                ->query()
                ->getQuery()
                ->select("{$p}.id", "{$p}.name", "{$p}.type", "{$p}.servertype", "{$s}.enable")
                ->leftJoin($s, "{$p}.id", '=', "{$s}.product_id")
                ->whereIn("{$p}.servertype", ['cpanelExtended', 'cpanel', 'directadmin', 'directadminExtended', 'plesk', 'PleskExtended'])
            ->whereNotIn("{$p}.id", function($query ) use ($p) {
                $query->select("{$p}.id")->from($p)->where("{$p}.type", "reselleraccount")->whereIn("{$p}.servertype",['plesk', 'PleskExtended']);
            });

        $dataProv = new providers\Providers\QueryDataProvider();
        $dataProv->setData($query);
        $dataProv->setDefaultSorting("id", 'DESC');
        $this->setDataProvider($dataProv); 
    }
}
