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

namespace ModulesGarden\WordpressManager\App\UI\Admin\Jobs;

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use \ModulesGarden\WordpressManager as main;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager\Core\Enum\Status;

/**
 * Description of PluginPackageDataTable
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class JobsDataTable extends DataTable implements AdminArea
{

    protected function loadHtml()
    {
        $this->initIds('jobsDataTable');
        $this->title = null;
        $j           = (new main\Core\Queue\Models\Job)->getTable();
        $this->addColumn((new Column('id'))->setOrderable('DESC'))
                ->addColumn((new Column('job', $j))->setSearchable(true, 'string'))
                ->addColumn((new Column('status', $j))->setSearchable(true, 'string'))
                ->addColumn((new Column('updated_at', $j))->setSearchable(true, 'date')->setOrderable())
                ->addColumn((new Column('created_at', $j))->setSearchable(true, 'date')->setOrderable());
    }

    public function initContent()
    {
        $this->addActionButton(new InfoButton);
        //delete
        $this->addActionButton(new DeleteButton);
        //mass delete
        $this->addMassActionButton(new MassDeleteButton);
    }

    public function replaceFieldid_($key, $row)
    {
        return $row->id;
    }

    public function replaceFieldJob($key, $row)
    {
        return sl('lang')->tr($row->job);
    }

    public function replaceFieldStatus($key, $row)
    {
        if (!$row->status)
        {
            $row->status = 'pending';
        }
        $label = new StatusLabel;
        $label->setStatus($row->status);
        return $label->getHtml();
    }

    public function replaceFieldUpdated_at($key, $row)
    {
        return main\App\Helper\WhmcsHelper::fromMySQLDate($row->$key, true);
    }

    public function replaceFieldCreated_at($key, $row)
    {
        return main\App\Helper\WhmcsHelper::fromMySQLDate($row->$key, true);
    }

    protected function loadData()
    {
        $j        = (new main\Core\Queue\Models\Job)->getTable();
        $query    = (new main\Core\Queue\Models\Job)
                ->query()
                ->getQuery()
                ->select("{$j}.id", "{$j}.job", "{$j}.status", "{$j}.created_at", "{$j}.updated_at");
        $dataProv = new main\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider();
        $dataProv->setDefaultSorting("id", 'desc');
        $dataProv->setData($query);
        $this->setDataProvider($dataProv);
    }
}
