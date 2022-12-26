<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Isos\Pages;

use ModulesGarden\Servers\HetznerVps\App\UI\Isos\Buttons\UnmountButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Isos\Helpers\IsoManager;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;
use ModulesGarden\Servers\HetznerVps\Core\ModuleConstants;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataTable;

/**
 * Description of Product
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class IsosPage extends DataTable implements ClientArea, AdminArea
{

    protected $id    = 'isosTable';
    protected $name  = 'isosTable';
    protected $title = 'isosTable';
    private $currentIso;

    public function loadHtml()
    {
        $this->addColumn((new Column('description'))->setOrderable('ASC')->setSearchable(true));
    }

    public function initContent()
    {
        $this->mainContainer->setScriptHtml(file_get_contents(ModuleConstants::getFullPath('app', 'UI', 'Isos', 'Templates', 'assets', 'js', 'index.js')));
        $this->addActionButton(new \ModulesGarden\Servers\HetznerVps\App\UI\Isos\Buttons\MountButton());
        $unmountButton = new UnmountButton();
        if(!$this->getCurrentIso()->id){
            $unmountButton->addClass("hidden");
        }
        $this->addButton($unmountButton);

    }

    public function replaceFieldDescription($key, $row)
    {
        if($this->currentIso->id && $this->currentIso->id == $row['id']){
            return sprintf("<b>%s</b>", $row['description']);
        }
        return $row[$key];
    }

    public function getCurrentIso(){
        if($this->currentIso){
            return $this->currentIso;
        }
        $dataManger   = new IsoManager($this->getWhmcsParams());
        return $this->currentIso = $dataManger->getIsoMounted();
    }

    protected function loadData()
    {
        $dataManger   = new IsoManager($this->getWhmcsParams());
        $data         = $dataManger->get();
        $this->getCurrentIso();
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setDefaultSorting('description', 'asc');

        foreach (  $data  as &$entery){
            $entery['description'] = sl("lang")->absoluteT( 'iso', $entery['description'] );
            $entery['status']   = false;
            if($entery['description'] == $this->currentIso->description)
            {
                $entery['status']   = true;
            }
        }

        $dataProvider->setData($data);
        $this->setDataProvider($dataProvider);
    }

    public function prepareAjaxData()
    {
    }

}
