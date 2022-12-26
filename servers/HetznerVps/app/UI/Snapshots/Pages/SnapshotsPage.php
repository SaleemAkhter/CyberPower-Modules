<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Pages;

use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Buttons\CreateButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Buttons\DeleteButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Buttons\DeleteMassButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Buttons\UpdateButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Helpers\SnapshotManager;
use ModulesGarden\Servers\HetznerVps\Core\ModuleConstants;
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
class SnapshotsPage extends DataTable implements ClientArea, AdminArea
{

    protected $id    = 'snapshotsTable';
    protected $name  = 'snapshotsTable';
    protected $title = 'snapshotsTable';

    public function loadHtml()
    {
        $this->addColumn((new Column('description'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('created'))->setOrderable('DESC')->setSearchable(true, Column::TYPE_DATE ));
        $this->addColumn((new Column('imageSize'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('status'))->setOrderable()->setSearchable(true));
    }

    public function initContent()
    {
        $this->mainContainer->setScriptHtml(file_get_contents(ModuleConstants::getFullPath('app', 'UI', 'Snapshots', 'Templates', 'assets', 'js', 'index.js')));
        $this->addActionButton(new \ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Buttons\RestoreButton());
        $createButton = new CreateButton();
        $dataManger   = new SnapshotManager($this->getWhmcsParams());
        if(!$dataManger ->isSizeLimit()){
            $createButton->addClass("hidden");
        }
        $this->addButton( $createButton ) ;
        $this->addActionButton(new UpdateButton());
        $this->addActionButton(new DeleteButton());
        $this->addMassActionButton(new DeleteMassButton());

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
        $dataManger   = new SnapshotManager($this->getWhmcsParams());
        $data=[];
        $current =  $dataManger->getCurrent();
        foreach ($dataManger->get()    as $entery){
            $date = new \DateTime($entery->created);
            $data[]=[
                'id' =>   $entery->id,
                'description' =>   $entery->description,
                'created' =>   date("Y-m-d H:i:s",  $date->getTimestamp()),
                'imageSize' =>   number_format($entery->imageSize, 2, '.', ''). " GB",
                'statusRaw' =>   $entery->status,
                'status' =>   sl("lang")->absoluteT( 'status',$entery->status ),
                'current' =>  $current->id == $entery->id
            ];
        }
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setDefaultSorting('created', 'DESC');
        $dataProvider->setData($data);
        $this->setDataProvider($dataProvider);
    }

}
