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

namespace ModulesGarden\WordpressManager\App\UI\Client\ResellerHosting;

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders as providers;
use \ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Helper\WhmcsHelper;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\Helper;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use \ModulesGarden\WordpressManager\Core\ModuleConstants;
use \ModulesGarden\WordpressManager\App\Models\ProductSetting;
use \ModulesGarden\WordpressManager\App\Models\InstanceImage;
/* * 
 * Description of InstallationPage
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */

class ProductDataTable extends DataTable implements ClientArea
{

    protected function loadHtml()
    {
        $this->initIds('productDataTable');
        
        $p        = (new Product())->getTable();
        $h        = (new Hosting())->getTable();
        $this->addColumn((new Column('name', $p))
                     ->setSearchable(true, Column::TYPE_STRING)
                      ->setOrderable('ASC'))
        ->addColumn((new Column('username', $h))
                        ->setSearchable(true, Column::TYPE_STRING)
                        ->setOrderable()
        )->addColumn((new Column('total'))
        );
    }

    public function initContent()
    { 
        $this->loadRequestObj();
        $rd = new ButtonRedirect;
        if($this->request->get('action')==='productdetails' && $this->request->get('mg-page')==='wordPressManager'){
            $baseUrl =  'clientarea.php?action=productdetails&id=' . $this->request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=index' ;
        }else{
            $baseUrl = BuildUrl::getUrl('home', 'reseller');
        }

        $rd->setRawUrl($baseUrl)
           ->setRedirectParams(['hostingId' => ':id']);
        $rd->setIcon('lu-zmdi lu-zmdi-edit');
        $this->addActionButton($rd);

    }

    public function replaceFieldTotal($key, $row)
    {
        return Installation::ofHostingId($row->id)->count();
    }

    public function replaceFieldName($key, $row)
    {
        /* @var $hosting main\App\Models\Whmcs\Hosting  */
        $hosting = main\App\Models\Whmcs\Hosting::find($row->id);
        return sprintf('<a href="clientarea.php?action=productdetails&id=%s">%s</a>', $row->id, $hosting->product->name);
    }

    protected function loadData()
    {
        $this->loadRequestObj();
        $p        = (new Product())->getTable();
        $s        = (new ProductSetting)->getTable();
        $h        = (new Hosting())->getTable();
        $query    = (new Hosting)
                ->query()
                ->getQuery()
                ->select("{$h}.id", "{$h}.username", "{$h}.packageid", "{$p}.name")
                ->leftJoin($p, "{$h}.packageid", '=',"{$p}.id")
                ->leftJoin($s, "{$p}.id", '=', "{$s}.product_id")
                ->where("{$h}.userid", $this->request->getSession('uid'))
                ->where("{$h}.domainstatus","Active")
                ->where("{$s}.enable","1")    
                ->where("{$p}.type","reselleraccount")  
                ->whereIn("{$p}.servertype", ['cpanelExtended', 'cpanel', 'directadmin', 'directadminExtended']);
        $dataProv = new providers\Providers\QueryDataProvider();
        $dataProv->setDefaultSorting("name", 'ASC');
        $dataProv->setData($query);
        $this->setDataProvider($dataProv);
    }
}
