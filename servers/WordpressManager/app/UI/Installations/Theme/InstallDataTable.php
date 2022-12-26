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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Theme;

use ModulesGarden\WordpressManager\App\Models\ThemeBlocked;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use ModulesGarden\WordpressManager\App\Models\Installation;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\RawDataTable\RawDataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;


/**
 * Description of themeInstalled
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class InstallDataTable extends  RawDataTable  implements ClientArea
{
    
    protected $id    = 'mg-theme-install';
    protected $name  = 'mg-theme-install-name';
    protected $title = 'mg-theme-install-title';
    
    protected function loadHtml()
    {
        $this->addColumn((new Column('name'))->setOrderable())
             ->addColumn((new Column('screenshot')))
             ->addColumn((new Column('description'))->setOrderable())
             ->addColumn((new Column('version'))->setOrderable())
             ->addColumn((new Column('rating'))->setOrderable()->setType('int'));
    }

    public function initContent()
    {
        $this->addMassActionButton(new InstallMassButton('themeInstallMassButton'));
        $this->addActionButton(new InstallButton('themeInstallButton'));
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
        
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request = Helper\sl('request');
        $data =[];
        try
        {
            if($request->get('sSearch')){
                /* @var  $installation  Installation */
                $installation = Installation::where('id', $request->get('wpid'))
                        ->where('user_id', $request->getSession('uid'))
                        ->firstOrFail();
                $module       = Wp::subModule($installation->hosting);
                if($installation->username){
                    $module ->setUsername($installation->username);
                }
                $res        = $module->getTheme($installation)->search($request->get('sSearch'));
                $res        = $this->removeBlockedThemes($installation, $res);
                foreach( $res as $d){
                    $d['id'] =  base64_encode(json_encode($d));
                    $data[]= $d;
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
        $dataProv = new ArrayDataProvider();
        $dataProv->setDefaultSorting("name", 'asc')->enableCustomSearch();
        $dataProv->setData((array) $data);
        $this->setDataProvider($dataProv);
    }

    private function removeBlockedThemes($installation, $res)
    {
        if(!$installation->hosting->productSettings->isThemeBlocked())
        {
            return $res;
        }
        if(!ThemeBlocked::where('product_id',$installation->hosting->packageid )->count()){
            return $res;
        }
        foreach($res as $k => $record){
            if($record['slug'] && !ThemeBlocked::where("slug" , $record['slug'] )->where('product_id',$installation->hosting->packageid )->count()){
                continue;
            }
            unset($res[$k]);
        }

        return $res;
    }
}
