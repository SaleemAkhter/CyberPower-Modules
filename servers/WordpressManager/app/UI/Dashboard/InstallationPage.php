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

namespace ModulesGarden\WordpressManager\App\UI\Dashboard;


use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders as providers;
use \ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Helper\WhmcsHelper;
use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\Helper;
use \ModulesGarden\WordpressManager\Core\Models\Whmcs\Client;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Product;

/* * 
 * Description of InstallationPage
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */

class InstallationPage extends DataTable implements AdminArea
{

    use RequestObjectHandler;
    protected $id    = 'labelscont';
    protected $name  = 'labelscont';
    // protected $title = "ljhkjhkhkjkjhjkh";

    protected function loadHtml()
    {
        $i        = (new Installation)->getTable();
        $c        = (new Client())->getTable();
        $p        = (new Product())->getTable();
        $h        = (new Hosting())->getTable();
        
        $this->addColumn((new Column('id', $i))
                        ->setSearchable(true, Column::TYPE_INT)
                        ->setOrderable('DESC'))
            ->addColumn((new Column('client', $c)))
            ->addColumn((new Column('name', $p))
                    ->setSearchable(true, Column::TYPE_STRING)
                    ->setOrderable())
            ->addColumn((new Column('hostingDomain',$h)))
            ->addColumn((new Column('domain', $i))
                    ->setSearchable(true, Column::TYPE_STRING)
                    ->setOrderable())
        ->addColumn((new Column('url', $i))
                        ->setSearchable(true, Column::TYPE_STRING)
                        ->setOrderable())
         ->addColumn((new Column('version', $i))
                        ->setSearchable(true, Column::TYPE_STRING)
                        ->setOrderable())
        ->addColumn((new Column('created_at', $i))
                        ->setSearchable(true, Column::TYPE_DATE)
                        ->setOrderable());
    }

    public function initContent()
    {
        
    }

    public function replaceFieldClient($key, $row)
    {
        return sprintf('<a href="clientssummary.php?userid=%d">%s %s</a>', $row->user_id, $row->firstname, $row->lastname);
    }
    
    public function replaceFieldName($key, $row)
    {
        return sprintf('<a href="configproducts.php?action=edit&id=%d">%s</a>', $row->packageid, $row->name);
    }
   
    public function replaceFieldHostingDomain($key, $row)
    {
        return sprintf('<a href="clientsservices.php?id=%d">%s</a>', $row->hosting_id, $row->hostingDomain);

    }
    
    public function replaceFieldDomain($key, $row)
    {
        return sprintf('<a href="%s" target="_blank">%s</a>', $row->url, $row->domain);
    }

    public function replaceFieldUrl($key, $row)
    {
        return sprintf('<a href="%s" target="blank">%s</a>', $row->url, $row->url);
    }

    public function replaceFieldCreated_at($key, $row)
    {
        return WhmcsHelper::fromMySQLDate($row->$key, true);
    }

    protected function loadData()
    {
        $i        = (new Installation)->getTable();
        $c        = (new Client())->getTable();
        $p        = (new Product())->getTable();
        $h        = (new Hosting())->getTable();
        $query    = (new Installation)
                ->query()
                ->getQuery()
                ->leftJoin($c, "{$i}.user_id", '=',"{$c}.id")
                ->leftJoin($h, "{$i}.hosting_id", '=',"{$h}.id")
                ->leftJoin($p, "{$h}.packageid", '=',"{$p}.id")
                ->select("{$i}.id", "{$i}.user_id", "{$c}.firstname","{$c}.lastname", "{$p}.name",
                         "{$h}.domain AS hostingDomain", "{$h}.packageid",
                         "{$i}.domain", "{$i}.hosting_id", "{$i}.url", "{$i}.path", "{$i}.version", "{$i}.created_at")
               ->groupBy("{$i}.id");          
        $dataProv = new providers\Providers\QueryDataProvider();
        $dataProv->setDefaultSorting("id", 'DESC');
        $dataProv->setData($query);
        $this->setDataProvider($dataProv);
    }
}
