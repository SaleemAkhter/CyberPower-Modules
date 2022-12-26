<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 12, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Admin\InstanceImage;

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use \ModulesGarden\WordpressManager as main;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;
/**
 * Description of PluginPackageDataTable
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class InstanceImageDataTable extends DataTable implements AdminArea
{
    use RequestObjectHandler;

    protected function loadHtml()
    {
        $this->initIds('instanceImageDataTable');
        $this->title = null;
        $ii = (new main\App\Models\InstanceImage)->getTable();
        $i  = (new main\App\Models\Installation)->getTable();
        $this->addColumn((new Column('name', $ii))->setSearchable(true, 'string')->setOrderable('ASC'))
             ->addColumn((new Column('domain',  $ii))->setSearchable(true, 'string'))
             ->addColumn((new Column('server_host',  $ii))->setSearchable(true, 'string'))
             ->addColumn((new Column('ftp_path', $ii))->setSearchable(true, 'string'))
             ->addColumn((new Column('installed_path', $ii))->setSearchable(true, 'string'))
             ->addColumn((new Column('created_at', $ii))->setSearchable(true, Column::TYPE_DATE)->setOrderable())
             ->addColumn((new Column('enable', $ii))->setSearchable(true, 'string'))
             ->addColumn((new Column('user_id', $ii))->setOrderable());
    }

    public function initContent()
    {
        $this->addButton(new CreateButton);
        $this->addActionButton(new UpdateButton);
        $this->addActionButton(new DeleteButton);
        //mass enable
        $this->addMassActionButton(new MassEnableButton);
        //mass disable
        $this->addMassActionButton(new MassDisableButton);
        //mass delete
        $this->addMassActionButton(new MassDeleteButton);
    }
    
    public function replaceFieldInstalled_path($key, $row)
    {
        return $row->installed_path ? $row->installed_path : '-';
    }
    
    public function replaceFieldCreated_at($key, $row)
    {
        return main\App\Helper\WhmcsHelper::fromMySQLDate($row->$key, true);
    }
    
    public function customColumnHtmlEnable()
    {
        return (new EnableSwitch)->getHtml();
    }
    
    public function replaceFieldUser_id($key, $row)
    {
        if($row->installation_id==0){
            return '-';
        }else if($row->user_id > 0 ){
            return  sl('lang')->tr('Yes');
        }else{
            return sl('lang')->tr('No');
        }
    }
    
    protected function loadData()
    {
        $ii = (new main\App\Models\InstanceImage)->getTable();
        $i  = (new main\App\Models\Installation)->getTable();
        $query    = (new main\App\Models\InstanceImage)
                ->query()
                ->getQuery()
                ->leftJoin($i, "{$ii}.installation_id", '=', "{$i}.id")
                ->select("{$ii}.id", "{$ii}.enable",  "{$ii}.name","{$ii}.domain", "{$ii}.server_host", "{$ii}.ftp_path", "{$ii}.installed_path",
                        "{$ii}.created_at", "{$i}.url","{$i}.version", "{$ii}.user_id","{$ii}.installation_id")
                ->groupBy("{$ii}.id");
        $dataProv = new main\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider();
        $dataProv->setDefaultSorting("name", 'ASC');
        $dataProv->setData($query);
        $this->setDataProvider($dataProv);
    }
}
