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

namespace ModulesGarden\WordpressManager\App\UI\Admin\PluginPackageItems;

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders as providers;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\RawDataTable\RawDataTable;
use \ModulesGarden\WordpressManager\App\Helper\WhmcsHelper;
use \ModulesGarden\WordpressManager\App\Http\Admin\BaseAdminController;
use \ModulesGarden\WordpressManager\App\Models\ModuleSettings;
use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use \ModulesGarden\WordpressManager\App\Models\PluginPackageItem;

/**
 * Description of PluginInstalled
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class ItemsAddDataTable extends RawDataTable implements AdminArea
{
    use BaseAdminController;
    use RequestObjectHandler;
    
    protected $id    = 'pluginItemsAdd';
    protected $name  = 'PluginItemsAddName';
    protected $title = 'PluginItemsAddTitle';

    protected function loadHtml()
    {
        $this->addColumn((new Column('name'))->setOrderable())
                ->addColumn((new Column('version'))->setOrderable())
                ->addColumn((new Column('last_updated'))->setOrderable())
                ->addColumn((new Column('requires'))->setOrderable())
                ->addColumn((new Column('tested'))->setOrderable())
                ->addColumn((new Column('rating'))->setOrderable()->setType('int'))
                ->addColumn((new Column('active_installs'))->setOrderable(DataProvider::SORT_DESC)->setType('int'));
    }

    public function initContent()
    {
        $this->addMassActionButton(new AddMassButton('addMassButton'));
        $this->addActionButton(new ItemAddButton());
        $this->setInternalAlertMessage('startSearchToDisplayData','info');
    }

    public function replaceFieldLast_updated($key, $row)
    {
        $d = new \DateTime($row['last_updated']);
        return WhmcsHelper::fromMySQLDate($d->format("Y-m-d H:i:s"), true);
    }

    public function replaceFieldActive_installs($key, $row)
    {
        return sprintf('%s+', $row['active_installs']);
    }

    public function replaceFieldRating($key, $row)
    {
        return sprintf('%s%s', $row['rating'], "%");
    }

    protected function loadData()
    {
        $data = [];
        $this->loadRequestObj();
        if ($this->getRequestValue('sSearch'))
        {
            $this->setInstallationId((new ModuleSettings())->getTestInstallationId());
            $data = $this->subModule()->pluginSearch($this->getInstallation(), $this->getRequestValue('sSearch'));
        }
        foreach ($data as $k => $v)
        {
            if (PluginPackageItem::where('slug', $v['slug'])->count())
            {
                unset($data[$k]);
            }
        }
        $dataProv = new providers\Providers\ArrayDataProvider();
        $dataProv->setDefaultSorting("active_installs", 'desc')->enableCustomSearch();
        $dataProv->setData((array) $data);
        $this->setDataProvider($dataProv);
    }
}
