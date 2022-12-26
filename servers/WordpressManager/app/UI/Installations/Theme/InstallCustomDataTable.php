<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Theme;

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\RawDataTable\RawDataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use \ModulesGarden\WordpressManager\App\Helper\CustomSoftware\GetProductCustomSoftware;

class InstallCustomDataTable extends  RawDataTable  implements ClientArea
{
    
    protected $id    = 'mg-custom-theme-install';
    protected $name  = 'mg-custom-theme-install-name';
    protected $title = 'mg-custom-theme-install-title';
    
    protected function loadHtml()
    {
        $this->addColumn((new Column('name'))->setOrderable())
             ->addColumn((new Column('description'))->setOrderable())
             ->addColumn((new Column('version'))->setOrderable());
    }

    public function initContent()
    {
        $this->addMassActionButton(new InstallCustomMassButton('themeInstallMassButton'));
        $this->addActionButton(new InstallCustomButton('themeInstallButton'));
    }
    
    public function replaceFieldName($key, $row)
    {
        return ucfirst(  $row['name'] );
    }

    public function replaceFieldDescription($key, $row)
    {
        return nl2br($row['description']);
    }
    
    protected function loadData()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request = Helper\sl('request');
        $data =[];
        try
        {
            $getRequest = $request->get('wpid');
            $getProductCustomSoftware = new GetProductCustomSoftware($getRequest);

            if($request->get('sSearch'))
            {
                $customThemeList = json_decode($getProductCustomSoftware->getCustomThemeList($request->get('sSearch')), true);
            } else
            {
                $customThemeList = json_decode($getProductCustomSoftware->getCustomThemeList(), true);
            }

            foreach ($customThemeList as $d) {
                $d['id'] = base64_encode(json_encode($d));
                $data[] = $d;
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
}
