<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Pages;

use ModulesGarden\DirectAdminExtended\App\Models\FunctionsSettings;
use ModulesGarden\DirectAdminExtended\App\Services\FTPEndPointService;
use ModulesGarden\DirectAdminExtended\Core\Models\Whmcs\Server;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\DataTable;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Buttons;
use function ModulesGarden\DirectAdminExtended\Core\Helper\sl;

class FtpEndPointsTable extends DataTable implements AdminArea
{
    protected $id    = 'ftpEndPointsTable';
    protected $name  = 'ftpEndPointsTable';
    protected $title = 'ftpEndPointsTableTitle';

    protected function loadHtml()
    {
        $this->addColumn((new Column('server_id'))->setSearchable(true)->setOrderable('ASC'))
            ->addColumn((new Column('name'))->setSearchable(true)->setOrderable(true))
            ->addColumn((new Column('host'))->setSearchable(true)->setOrderable(true))
            ->addColumn((new Column('port'))->setSearchable(true)->setOrderable(true))
            ->addColumn((new Column('user'))->setSearchable(true)->setOrderable(true))
            ->addColumn(new Column('password'))
            ->addColumn((new Column('path'))->setSearchable(true)->setOrderable(true))
            ->addColumn(new Column('admin_access'))
            ->addColumn(new Column('enable_restore'));
    }

    public function initContent()
    {
        $this->addButton(new Buttons\CreateEndPoint())
            ->addActionButton(new Buttons\EditEndPoint())
            ->addActionButton(new Features\Buttons\DeleteEndPoint());
    }

    public function replaceFieldServer_Id($key, $row)
    {
        return (new Server())->where('id', (int)$row[$key])->first()->name;
    }

    public function replaceFieldPassword($key, $row)
    {
        return str_repeat('*', strlen($row[$key]));
    }

    public function replaceFieldAdmin_Access($key, $row)
    {
        if ($row[$key] === 'on')
        {
            return '<i class="icon-in-button lu-zmdi lu-zmdi-check"></i>';
        }
    }

    public function replaceFieldEnable_Restore($key, $row)
    {
        if ($row[$key] === 'on')
        {
            return '<i class="icon-in-button lu-zmdi lu-zmdi-check"></i>';
        }
    }

    protected function loadData()
    {
        $request       = sl('request');
        $ftpEndService = new FTPEndPointService();
        $result        = $ftpEndService->findByCriteria(['product_id' => $request->get('pid')]);
        $result        = $result ? $result->toArray() : [];

        $dataProv = new ArrayDataProvider();
        $dataProv->setData($result);
        $dataProv->setDefaultSorting('server', 'ASC');

        $this->setDataProvider($dataProv);
    }
}
