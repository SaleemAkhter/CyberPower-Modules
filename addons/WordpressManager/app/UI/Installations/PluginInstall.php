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

namespace ModulesGarden\WordpressManager\App\UI\Installations;

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders as providers;
use ModulesGarden\WordpressManager\App\Models\Installation;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\RawDataTable\RawDataTable;
use \ModulesGarden\WordpressManager\App\Helper\WhmcsHelper;
use \ModulesGarden\WordpressManager\App\Models\PluginBlocked;
use \ModulesGarden\WordpressManager\App\UI\Installations\Buttons\PluginInfoButton;

/**
 * Description of PluginInstalled
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PluginInstall extends  RawDataTable  implements ClientArea
{
    
    protected $id    = 'mg-plugin-install';
    protected $name  = 'mg-plugin-install-name';
    protected $title = 'mg-plugin-install-title';
    /**
     * @var Installation Description
     */
    private $installation;
    
    protected function loadHtml(){
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
        $this->addActionButton(new PluginInfoButton('pluginInforButton'));
        $this->addActionButton(new Buttons\PluginInstallButton('pluginInstallButton'));
        $this->setInternalAlertMessage('startSearchToDisplayData','info');
    }
    
    public function replaceFieldLast_updated($key, $row)
    {
        $d = new \DateTime($row['last_updated']);
        return WhmcsHelper::fromMySQLDate($d->format("Y-m-d H:i:s"), false); 
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
        try
        {
            /* @var  $installation  Installation */
            $this->installation = Installation::where('id', $request->get('wpid'))
                    ->where('user_id', $request->getSession('uid'))
                    ->firstOrFail();
            $module       = Wp::subModule($this->installation->hosting);
            if( $this->installation->username){
                $module ->setUsername( $this->installation->username);
            }
            if($request->get('sSearch')){
                $data         = $module->pluginSearch($this->installation, $request->get('sSearch'));
                $this->removeBlocked($data);
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
    
    private function removeBlocked(&$data){
        
        if(!$this->installation->hosting->productSettings->isPluginBlocked()){
            return;
        }
        if(!PluginBlocked::where('product_id',$this->installation->hosting->packageid )->count()){
            return;
        }
        foreach($data as $k => $record){
            if($record['slug'] && !PluginBlocked::where("slug" , $record['slug'] )->where('product_id',$this->installation->hosting->packageid )->count()){
                continue;
            }
            unset($data[$k]);
        }
    }
}
