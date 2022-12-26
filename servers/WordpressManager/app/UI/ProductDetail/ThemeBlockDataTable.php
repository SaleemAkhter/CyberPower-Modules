<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders as providers;
use ModulesGarden\WordpressManager\App\Models\Installation;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\RawDataTable\RawDataTable;
use \ModulesGarden\WordpressManager\App\Helper\WhmcsHelper;
use \ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
use ModulesGarden\WordpressManager\App\Models\ThemeBlocked;

class ThemeBlockDataTable extends  RawDataTable  implements AdminArea
{
    
    protected $id    = 'mg-theme-block';
    protected $name  = 'mg-theme-block-name';
    protected $title = 'mg-theme-block-title';
    
    protected function loadHtml()
    {
        $this->addColumn((new Column('name'))->setOrderable())
             ->addColumn((new Column('screenshot')))
             ->addColumn((new Column('description'))->setOrderable())
             ->addColumn((new Column('last_updated'))->setOrderable())
             ->addColumn((new Column('version'))->setOrderable())
             ->addColumn((new Column('rating'))->setOrderable()->setType('int'));
    }

    public function initContent()
    {
        $this->addMassActionButton(new ThemeBlockMassButton('themeBlockedMassButton'));
        $this->addActionButton(new ThemeBlockButton('themeBlockButton'));
        $request = Helper\sl('request');
        $productSetting = (new ProductSettingRepository)->forProductId($request->get('id'));
        if(!$productSetting->getTestInstallation()){
            $this->setInternalAlertMessage('addBlocedTheme','danger');
        }else{
            $this->setInternalAlertMessage('startSearchToDisplayData','info');
        }
    }
    
    public function replaceFieldLast_updated($key, $row)
    {
        $d = new \DateTime($row['last_updated']);
        return WhmcsHelper::fromMySQLDate($d->format("Y-m-d H:i:s"), true);
    }

    public function replaceFieldRating($key, $row)
    {
       
        return sprintf('%s%s', $row['rating'],"%");
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
                $data = $module->getTheme($installation)->search($this->getRequestValue('sSearch'));
            }
            if($data == '[]')
            {
                $data = null;
            }
            foreach($data as $k => $v){
                if(ThemeBlocked::where('slug',$v['slug'])->where('product_id', $request->get('id'))->count()){
                    unset($data[$k]);
                } else {
                    $infoArray = ['name' => $data[$k]['name'], 'slug' => $data[$k]['slug']];
                    $data[$k]['id'] = base64_encode(json_encode($infoArray));
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
        $dataProv->setDefaultSorting("name", 'desc')->enableCustomSearch();
        $dataProv->setData((array) $data);
        $this->setDataProvider($dataProv);
    }
}
