<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Backups\Pages;

use ModulesGarden\Servers\HetznerVps\App\UI\Backups\Helpers\BackupsManager;
use ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Pages\AlertBox;
use ModulesGarden\Servers\HetznerVps\Core\ModuleConstants;
use ModulesGarden\Servers\HetznerVps\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataTable;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

/**
 * Description of Product
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class BackupsPage extends DataTable implements ClientArea, AdminArea
{

    protected $id    = 'backupsTable';
    protected $name  = 'backupsTable';
    protected $title = 'backupsTable';

    public function loadHtml()
    {
        $this->addColumn((new Column('description'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('created'))->setOrderable('DESC')->setSearchable(true, Column::TYPE_DATE));
        $this->addColumn((new Column('imageSize'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('status'))->setOrderable()->setSearchable(true));
    }

    public function initContent()
    {
        $this->mainContainer->setScriptHtml(file_get_contents(ModuleConstants::getFullPath('app', 'UI', 'Backups', 'Templates', 'assets', 'js', 'index.js')));
        $this->addActionButton(new \ModulesGarden\Servers\HetznerVps\App\UI\Backups\Buttons\RestoreButton());
    }

    public function replaceFieldDescription($key, $row)
    {
        if($row['current']){
            return sprintf("<b>%s</b>", $row['description']);
        }
        return $row[$key];
    }

    public function replaceFieldStatus($key, $row){
        if($row['statusRaw']=='available'){
            return '<span class="lu-label lu-label--success lu-label--status">' . $row['status'] . '</span>';
        }
        return '<span class="lu-label lu-label--warning lu-label--status">' . $row['status']  . '</span>';

    }

    protected function loadData()
    {
        $dataManger   = new BackupsManager($this->getWhmcsParams());
        $dataProvider = new ArrayDataProvider();
        $data=[];
        $current =  $dataManger->getCurrent();
        foreach ($dataManger->get() as &$entry){
            $date = new \DateTime($entry->created);
            $data[]=[
                'id' =>   $entry->id,
                'description' =>   $entry->description,
                'created' =>   date("Y-m-d H:i:s",  $date->getTimestamp()),
                'imageSize' =>   number_format($entry->imageSize, 2, '.', ''). " GB",
                'statusRaw' =>   $entry->status,
                'status' =>   sl("lang")->absoluteT( 'status',$entry->status ),
                'current' =>  $current->id == $entry->id
            ];
        }
        $dataProvider->setDefaultSorting('created', 'desc');
        $dataProvider->setData($data);

        $this->setDataProvider($dataProvider);
        
    }

}
