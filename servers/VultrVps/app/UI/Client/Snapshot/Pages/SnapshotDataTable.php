<?php
/* * ********************************************************************
*  VultrVps Product developed. (26.03.19)
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

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Pages;

use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\App\Helpers\Format;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Buttons\CreateButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Buttons\DeleteButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Buttons\DeleteMassButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Buttons\MailtoSwitchButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Buttons\RestoreButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Buttons\UpdateButton;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\DataTable\DataTable;
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;

class SnapshotDataTable extends DataTable implements ClientArea
{

    protected $id = 'snapshotDataTable';
    protected $title = 'snapshotDataTable';

    public function initContent()
    {
        //Create
        $this->addButton(new CreateButton());
        //Restore
        $this->addActionButton(new RestoreButton());
        //Update
        $this->addActionButton(new UpdateButton());
        //Delete
        $this->addActionButton(new DeleteButton());
        //Mass delete
        $this->addMassActionButton(new DeleteMassButton());
    }

    protected function loadHtml()
    {
        $this->addColumn((new Column('description'))->setSearchable(true, "string")->setOrderable())
             ->addColumn((new Column('status'))->setSearchable(true, "string")->setOrderable())
             ->addColumn((new Column('size'))->setOrderable())
             ->addColumn((new Column('dateCreated'))->setOrderable('DESC'));

    }


    public function replaceFieldStatus($key, $row)
    {
        if ($row['status']== 'complete')
        {
            return '<span class="lu-label lu-label--success lu-label--status">' . sl('lang')->abtr($row['status']) . '</span>';
        }
        return '<span class="lu-label lu-label--danger lu-label--status">' . sl('lang')->abtr($row['status']) . '</span>';
    }

    public function replaceFieldDateCreated($key, $row)
    {
        $date = new \DateTime($row[$key]);
        return  $date->format('Y-m-d H:i:s');
    }

    public function replaceFieldSize($key, $row)
    {
        return Format::convertBytes($row['size']);
    }

    protected function loadData()
    {
        $data = [];
        $snapshots = (new InstanceFactory())->fromWhmcsParams()->snapshots();
        foreach ($snapshots->get() as $entity)
        {
            $data[] = $entity->toArray();
        }
        $dataProv = new ArrayDataProvider();
        $dataProv->setDefaultSorting("dateCreated", 'DESC');
        $dataProv->setData($data);
        $this->setDataProvider($dataProv);
    }

}