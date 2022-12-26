<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations;

use ModulesGarden\WordpressManager\App\Helper\CustomSoftware\GetProductCustomSoftware;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders as providers;
use ModulesGarden\WordpressManager\App\Models\Installation;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\RawDataTable\RawDataTable;

class PluginCustomInstall extends  RawDataTable  implements ClientArea
{
    
    protected $id    = 'mg-custom-plugin-install';
    protected $name  = 'mg-custom-plugin-install-name';
    protected $title = 'mg-custom-plugin-install-title';
    /**
     * @var Installation Description
     */
    private $installation;
    
    protected function loadHtml(){
        $this->addColumn((new Column('name'))->setOrderable())
            ->addColumn((new Column('description'))->setOrderable())
            ->addColumn((new Column('version'))->setOrderable());
    }

    public function initContent()
    {
        $this->addMassActionButton(new Buttons\CustomPluginInstallMassButton('customPluginInstallMassButton'));
        $this->addActionButton(new Buttons\CustomPluginInstallButton('customPluginInstallButton'));
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
                $customPluginList = json_decode($getProductCustomSoftware->getCustomPluginList($request->get('sSearch')), true);
            } else
            {
                $customPluginList = json_decode($getProductCustomSoftware->getCustomPluginList(), true);
            }

            foreach( $customPluginList as $d){
                $d['id'] =  base64_encode(json_encode($d));
                $data[]= $d;
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
