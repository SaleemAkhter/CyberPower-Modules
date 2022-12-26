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

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders as providers;
use ModulesGarden\WordpressManager\App\Models\Installation;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\RawDataTable\RawDataTable;
use \ModulesGarden\WordpressManager\App\Helper\WhmcsHelper;
use \ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
use ModulesGarden\WordpressManager\App\Models\PluginBlocked;
/**
 * Description of PluginInstalled
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PluginBlockDataTable extends  RawDataTable  implements AdminArea
{
    
    protected $id    = 'mg-plugin-block';
    protected $name  = 'mg-plugin-block-name';
    protected $title = 'mg-plugin-block-title';
    
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
        $this->addMassActionButton(new PluginBlockMassButton('pluginBlockedMassButton'));
        $this->addActionButton(new PluginBlockButton('pluginBlockButton'));
        $request = Helper\sl('request');
        $productSetting = (new ProductSettingRepository)->forProductId($request->get('id'));
        if(!$productSetting->getTestInstallation()){
            $this->setInternalAlertMessage('addBlocedPlugin','danger');
        }else{
            $this->setInternalAlertMessage('startSearchToDisplayData','info');
        }
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
       
        return sprintf('%s%s', $row['rating'],"%");
    }
    
    
    protected function loadData()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request = Helper\sl('request');
        $data =[];
        $productSetting = (new ProductSettingRepository)->forProductId($request->get('id'));
        if(!$productSetting->getTestInstallation()){
            return;
        }
        try
        {
            /* @var  $installation  Installation */
            $installation = Installation::where('id',$productSetting->getTestInstallation() ) 
                    ->firstOrFail();
            $module       = Wp::subModule($installation->hosting);
            if($request->get('sSearch')){ 
                $data         = $module->pluginSearch($installation, $request->get('sSearch'));
            }
            if($data == '[]')
            {
                $data = null;
            }
            foreach($data as $k => $v){
                if(PluginBlocked::where('slug',$v['slug'])->where('product_id', $request->get('id'))->count()){
                    unset($data[$k]);
                }
            }
        }
        catch (\Exception $ex)
        {
            // Record the error in WHMCS's module log.
            logModuleCall(
                'WordpressManager',
                __FUNCTION__,
                $ex->getMessage(),
                $ex->getTraceAsString()
            );
            throw $ex;
        }
        $dataProv = new providers\Providers\ArrayDataProvider();
        $dataProv->setDefaultSorting("active_installs", 'desc')->enableCustomSearch();
        $dataProv->setData((array) $data);
        $this->setDataProvider($dataProv);
    }
}
