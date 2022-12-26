<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Pages;

use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\App\Helpers\Format;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Buttons\BackupScheduleButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Buttons\DeleteButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Buttons\DeleteMassButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Buttons\MailtoSwitchButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Buttons\RestoreButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Buttons\UpdateButton;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\DataTable\DataTable;
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;

class BackupDataTable extends DataTable implements ClientArea
{

    protected $id = 'backupDataTable';
    protected $title = 'backupDataTable';
    protected $automaticBackups = true;

    /**
     * @return bool
     */
    public function isAutomaticBackups()
    {
        return $this->automaticBackups;
    }

    /**
     * @param bool $automaticBackups
     * @return BackupDataTable
     */
    public function setAutomaticBackups( $automaticBackups){
        $this->automaticBackups = $automaticBackups;
        return $this;
    }

    public function initContent()
    {
        //ScheduleButton
        $backupScheduleButton = new BackupScheduleButton();
        if(!$this->isAutomaticBackups()){
            $backupScheduleButton->addClass('disabled');
        }
        $this->addButton($backupScheduleButton);
        //Restore
        $this->addActionButton(new RestoreButton());
        //Delete
        $this->addActionButton(new DeleteButton());
        //Delete mass
        $this->addMassActionButton(new DeleteMassButton());
    }

    protected function loadHtml()
    {
        $this->addColumn((new Column('description'))->setSearchable(true, "string")->setOrderable())
             ->addColumn((new Column('status'))->setSearchable(true, "string")->setOrderable())
             ->addColumn((new Column('size'))->setSearchable(true)->setOrderable())
             ->addColumn((new Column('dateCreated'))->setSearchable(true, "date")->setOrderable('DESC'));
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
        $backups = (new InstanceFactory())->fromWhmcsParams()->backups();
        $backups->findIp($this->getWhmcsParamByKey('model')->dedicatedip);
        foreach ($backups->get() as $entity)
        {
            $data[] = $entity->toArray();
        }
        $dataProv = new ArrayDataProvider();
        $dataProv->setDefaultSorting("dateCreated", 'DESC');
        $dataProv->setData($data);
        $this->setDataProvider($dataProv);
    }

}