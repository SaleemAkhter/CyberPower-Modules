<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Pages;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\DataTable;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Buttons;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\DirectAdminExtended\App\Services\BackupPathService;
use ModulesGarden\DirectAdminExtended\Core\Models\Whmcs\Server;
use function ModulesGarden\DirectAdminExtended\Core\Helper\sl;

class BackupTable extends DataTable implements AdminArea
{
    protected $id    = 'backupTable';
    protected $name  = 'backupTable';
    protected $title = 'backupTableTitle';

    protected function loadHtml()
    {
        $this->addColumn((new Column('server_id'))->setSearchable(true)->setOrderable('ASC'))
            ->addColumn((new Column('name'))->setSearchable(true)->setOrderable(true))
            ->addColumn((new Column('path'))->setSearchable(true)->setOrderable(true))
            ->addColumn(new Column('admin_access'))
            ->addColumn(new Column('enable_restore'));
    }

    public function initContent()
    {
        $this->addButton(new Buttons\CreateBackup())
            ->addActionButton(new Buttons\EditBackup())
            ->addActionButton(new Buttons\DeleteBackup());
    }

    public function replaceFieldServer_Id($key, $row)
    {
        return (new Server())->where('id', (int)$row[$key])->first()->name;
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
        $ftpEndService = new BackupPathService();
        $result        = $ftpEndService->findByCriteria(['product_id' => $request->get('pid')]);
        $result        = $result ? $result->toArray() : [];

        $dataProv = new ArrayDataProvider();
        $dataProv->setData($result);
        $dataProv->setDefaultSorting('server', 'ASC');

        $this->setDataProvider($dataProv);
    }
}
