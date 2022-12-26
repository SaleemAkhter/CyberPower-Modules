<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 7, 2017)
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

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders as providers;
use ModulesGarden\WordpressManager\App\Models\Installation;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager\App\UI\Installations\Sidebars\Actions;
/* * gb
 * Description of InstallationPage
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */

class ConfigDataTable extends DataTable implements ClientArea
{
    protected $id    = 'mg-config-data-table';
    protected $name  = 'mg-config-data-tabl-name';
    protected $title = 'mg-config-data-tabl-title';

    protected function loadHtml()
    {
        $this->addColumn((new Column('name'))->setSearchable(true, Column::TYPE_STRING)
                        ->setOrderable(DataProvider::SORT_DESC)
                )
                ->addColumn((new Column('value'))->setSearchable(true)->setOrderable())
                ->addColumn((new Column('type'))->setSearchable(true)->setOrderable());
    }


    public function initContent()
    {
        //Sidebar
        sl('sidebar')->add( new Actions('actions')); 
        //Create 
        $this->addButton(new Buttons\ConfigCreateButton('configCreateButton'));
        $this->addActionButton(new Buttons\ConfigUpdateButton('configUpdateButton'));
        $this->addActionButton(new Buttons\ConfigDeleteButton('configDeleteButton'));
    }

    protected function loadData()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request = Helper\sl('request');
        try
        {
            /* @var  $installation  Installation */
            $installation = Installation::where('id', $request->get('wpid'))
                    ->where('user_id', $request->getSession('uid'))
                    ->firstOrFail();
            $module       = Wp::subModule($installation->hosting);
            if($installation->username){
                $module ->setUsername($installation->username);
            }
            $data=[];
            foreach($module->getConfig($installation)->getList() as $d){
                if(!$d['name'] && $d['key']){
                    $d['name'] = $d['key'];
                }
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
        $dataProv->setDefaultSorting("name", 'asc');
        $dataProv->setData((array) $data);
        $this->setDataProvider($dataProv);
    }
}
