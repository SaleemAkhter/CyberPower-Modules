<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Pages;

use ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Helpers\ImageManager;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataTable;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

/**
 * Description of Product
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class RebuildPage extends DataTable implements ClientArea, AdminArea
{

    protected $id    = 'rebuildTable';
    protected $name  = 'rebuildTable';
    protected $title = 'rebuildTable';

    public function loadHtml()
    {
        $this
            ->addColumn((new Column('name'))->setOrderable('ASC')->setSearchable(true))
            ->addColumn((new Column('distribution'))->setOrderable()->setSearchable(true));
    }

    public function initContent()
    {
        $this->addActionButton(new \ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Buttons\RebuildConfirm());
    }

    protected function loadData()
    {
        $dataManger   = new ImageManager($this->getWhmcsParams());
        $data         = $dataManger->getAllTemplatesExceptBackups();
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setDefaultSorting('name', 'asc');
        foreach (  $data  as &$entery){
            $entery['distribution'] = sl("lang")->absoluteT( 'distribution', $entery['distribution'] );

            $name = sl("lang")->absoluteT('template', $entery['name'] );
            $entery['name'] = strpos($name, 'LANG') === false ? $name : $entery['name'];
        }
        $dataProvider->setData($data);
        $this->setDataProvider($dataProvider);
    }

}
