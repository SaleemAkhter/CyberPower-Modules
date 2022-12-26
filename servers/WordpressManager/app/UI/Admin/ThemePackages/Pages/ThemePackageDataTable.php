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

namespace ModulesGarden\WordpressManager\App\UI\Admin\ThemePackages\Pages;

use ModulesGarden\WordpressManager\App\Models\ThemePackage;
use ModulesGarden\WordpressManager\App\Models\ThemePackageItem;
use ModulesGarden\WordpressManager\App\UI\Admin\ThemePackages\Buttons\DeleteButton;
use ModulesGarden\WordpressManager\App\UI\Admin\ThemePackages\Buttons\EnableSwitch;
use ModulesGarden\WordpressManager\App\UI\Admin\ThemePackages\Buttons\MassDeleteButton;
use ModulesGarden\WordpressManager\App\UI\Admin\ThemePackages\Buttons\MassDisableButton;
use ModulesGarden\WordpressManager\App\UI\Admin\ThemePackages\Buttons\MassEnableButton;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonRedirect;
use \ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\DataProvider;

/**
 * Description of ThemePackageDataTable
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class ThemePackageDataTable extends DataTable implements AdminArea
{
    protected function loadHtml()
    {
        $this->initIds('themePackageDataTable');
        $this->title = null;
        $pp = (new ThemePackage)->getTable();
        $pi = (new ThemePackageItem())->getTable();
        $this->addColumn((new Column('name', $pp))->setSearchable(true, 'string')->setOrderable(DataProvider::SORT_ASC))
                ->addColumn((new Column('description', $pp))->setSearchable(true, 'string'))
                ->addColumn((new Column('slug', $pi))->setSearchable(true, 'string'))
                ->addColumn((new Column('enable', $pp))->setSearchable(true, 'string'));
    }

    public function initContent()
    {
        //create
        $buttonRedirect = new ButtonRedirect('create');  
        $buttonRedirect->setShowTitle();
        $buttonRedirect->setRawUrl('addonmodules.php?module=WordpressManager&mg-page=Themes&mg-action=create');
        $buttonRedirect->setIcon('lu-icon-in-button lu-zmdi lu-zmdi-plus');
        $buttonRedirect->replaceClasses(['lu-btn lu-btn--primary']);
        $buttonRedirect->deleteHtmlAttribute('data-toggle');
        $this->addButton($buttonRedirect);
        //update
        $buttonRedirect = new ButtonRedirect('update');
        $buttonRedirect->setRawUrl('addonmodules.php?module=WordpressManager&mg-page=Themes&mg-action=update')
                ->setRedirectParams(['id' => ':id']);
        $buttonRedirect->setIcon('lu-icon-in-button lu-zmdi lu-zmdi-edit');
        $buttonRedirect->replaceClasses(['lu-btn lu-btn--sm lu-btn--default lu-btn--link lu-btn--icon lu-btn--plain']);
        $this->addActionButton($buttonRedirect);
        //delete
        $this->addActionButton(new DeleteButton());
        //mass enable
        $this->addMassActionButton(new MassEnableButton());
        //mass disable
        $this->addMassActionButton(new MassDisableButton());
        //mass delete
        $this->addMassActionButton(new MassDeleteButton());
    }
    
    public function replaceFieldSlug($key, $row)
    {
        $id = $row->id;
        $items = (array) ThemePackageItem::where("theme_package_id",$id)->pluck('name')->toArray();
        return implode(", ", $items);
    }
    
    public function customColumnHtmlEnable()
    {
        return (new EnableSwitch())->getHtml();
    }

    public function replaceFieldDescription($key, $row){
        return nl2br($row->description);
    }

    protected function loadData()
    {
        $tp       = (new ThemePackage)->getTable();
        $ti       = (new ThemePackageItem())->getTable();
        $query    = (new ThemePackage)
                ->query()
                ->getQuery()
                ->leftJoin($ti, "{$ti}.theme_package_id", '=', "{$tp}.id")
                ->select("{$tp}.*")
                ->groupBy("{$tp}.id");
        $dataProv = new QueryDataProvider();
        $dataProv->setDefaultSorting("name", 'ASC');
        $dataProv->setData($query);
        $this->setDataProvider($dataProv);
    }
}
