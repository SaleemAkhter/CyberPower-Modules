<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 12, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Client\PluginPackages;

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager as main;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager\App\UI\Installations\Sidebars\Actions;

/**
/**
 * Description of PluginPackageDataTable
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PluginPackageDataTable extends DataTable implements ClientArea
{
    use main\App\Http\Client\BaseClientController;

    protected function loadHtml()
    {
        $this->initIds('pluginPackageDataTable');
        $pp = (new main\App\Models\PluginPackage)->getTable();
        $pi = (new main\App\Models\PluginPackageItem())->getTable();
        $this->addColumn((new Column('name', $pp))->setSearchable(true, 'string')->setOrderable('ASC'))
             ->addColumn((new Column('description', $pp))->setSearchable(true, 'string')->setOrderable(true));
    }

    public function initContent()
    {
        //Sidebar
        sl('sidebar')->add( new Actions('actions')); 
        $this->addActionButton(new InstallButton);
    }

    public function replaceFieldDescription($key, $row){
        return nl2br($row->description);
    }

    protected function loadData()
    {
        $this->loadRequestObj();
        $this->reset();
        $this->setInstallationId($this->request->get('wpid'))
             ->setUserId($this->request->getSession('uid'));
        $pp       = (new main\App\Models\PluginPackage)->getTable();
        $query    = (new main\App\Models\PluginPackage)
                ->query()
                ->getQuery()
                ->select("{$pp}.*")
                ->where("enable",1)
                ->whereIn("{$pp}.id", (array) $this->getInstallation()->hosting->productSettings->getPluginPackages())
                ->groupBy("{$pp}.id");
        $dataProv = new main\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider();
        $dataProv->setDefaultSorting("name", 'ASC');
        $dataProv->setData($query);
        $this->setDataProvider($dataProv);
    }
}
