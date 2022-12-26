<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Helpers\BackupsManager;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class BackupsList extends DataTable implements ClientArea, AdminArea {

    protected $id    = 'backupsTable';
    protected $name  = 'backupsTable';
    protected $title = 'backups';
    public function loadHtml() {
        $this->addColumn((new Column('id'))->setOrderable()->setSearchable(true))
                ->addColumn((new Column('name'))->setOrderable()->setSearchable(true))
                ->addColumn((new Column('date'))->setOrderable('DESC')->setSearchable(true));         
    }
  
    public function initContent() {
//        $this->addActionButton(new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Buttons\Delete());
        $this->addActionButton(new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Buttons\Restore());
        //$this->backupsEnabled();
    }
    
    private function backupsEnabled(){
        $dataManger   = new BackupsManager($this->whmcsParams);
       $this->backupsEnabled = $dataManger->backupsEnabled();
    }
    
    private function backupStart(){
         $dataManger   = new BackupsManager($this->whmcsParams);
         return $dataManger->dateStart();
    }
    
    private function backupEnd(){
         $dataManger   = new BackupsManager($this->whmcsParams);
         return $dataManger->dateEnd();
    }
    
    protected function loadData() {
        $dataManger   = new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Helpers\BackupsManager($this->whmcsParams);
        $data         = $dataManger->getBackupsToTable();
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setDefaultSorting('date', 'desc');
        $dataProvider->setData($data);
        $this->setDataProvider($dataProvider);
    }

}
