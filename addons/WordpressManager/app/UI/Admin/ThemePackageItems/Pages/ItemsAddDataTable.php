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
use ModulesGarden\WordpressManager\App\UI\Admin\ThemePackageItems\Buttons\AddMassButton;
use ModulesGarden\WordpressManager\App\UI\Admin\ThemePackageItems\Buttons\ItemAddButton;
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
    
    protected $id    = 'themeItemsAdd';
    protected $name  = 'themeItemsAddName';
    protected $title = 'themeItemsAddTitle';

    protected function loadHtml()
    {
        $this->addColumn((new Column('name'))->setOrderable(DataProvider::SORT_ASC))
            ->addColumn((new Column('screenshot')))
            ->addColumn((new Column('description'))->setOrderable())
            ->addColumn((new Column('version'))->setOrderable())
            ->addColumn((new Column('rating'))->setOrderable()->setType('int'));
    }

    public function initContent()
    {
        $this->addMassActionButton(new AddMassButton('addMassButton'));
        $this->addActionButton(new ItemAddButton());
        $this->setInternalAlertMessage('startSearchToDisplayData','info');
    }

    public function replaceFieldName($key, $row)
    {
        return ucfirst(  $row['name'] );
    }

    public function replaceFieldScreenshot($key, $row)
    {
        $screenshotUrl = $row['screenshot_url'];
        if(!preg_match('/https\:/', $screenshotUrl) && !preg_match('/http\:/', $screenshotUrl) ){
            $screenshotUrl ="https:".$screenshotUrl;
        }
        return "
                 <a href=\"{$row['preview_url']}\" target=\"blank\"> <img src=\"{$screenshotUrl}\"  style=\" max-height:none; max-width: 150px;\"></a>
                ";
    }

    public function replaceFieldRating($key, $row)
    {
        return sprintf('%s%s', $row['rating'],"%");
    }

    protected function loadData()
    {
        $data = [];
        $this->loadRequestObj();
        if ($this->getRequestValue('sSearch'))
        {
            $this->setInstallationId((new ModuleSettings())->getTestInstallationId());
            $enteries = $this->subModule()->getTheme($this->getInstallation())->search($this->getRequestValue('sSearch'));
            foreach($enteries as $d){
                $d['id'] =  base64_encode(json_encode($d));
                $data[] = $d;
            }
        }
        foreach ($data as $k => $v)
        {
            if (ThemePackageItem::where('slug', $v['slug'])->count())
            {
                unset($data[$k]);
            }
        }
        $dataProv = new providers\Providers\ArrayDataProvider();
        $dataProv->setDefaultSorting("name", 'asc')->enableCustomSearch();
        $dataProv->setData((array) $data);
        $this->setDataProvider($dataProv);
    }
}
