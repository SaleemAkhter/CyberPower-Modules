<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Pages\Plugin;

use ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\EnablePluginSwitch;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Buttons\Plugin\CreatePluginButton;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Buttons\Plugin\UpdatePluginButton;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Buttons\Plugin\DeletePluginButton;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Buttons\Plugin\MassDeletePluginButton;
use \ModulesGarden\WordpressManager as main;

class CustomPluginsPage extends DataTable implements AdminArea
{
    protected $id    = 'customPluginsPage';
    protected $name  = 'customPluginsPage';
    protected $title = null;

    use RequestObjectHandler;

    protected function loadHtml()
    {
        $this->initIds('customPluginsDataTable');
        $this->title = null;
        $ii = (new main\App\Models\CustomPlugin())->getTable();
        $this->addColumn((new Column('name', $ii))->setSearchable(true, 'string')->setOrderable('ASC'))
            ->addColumn((new Column('description',  $ii))->setSearchable(true, 'string'))
            ->addColumn((new Column('version',  $ii))->setSearchable(true, 'string'))
            ->addColumn((new Column('enable',  $ii))->setSearchable(true, 'string'));
    }

    public function initContent()
    {
        $this->addButton(new CreatePluginButton());
        $this->addActionButton(new UpdatePluginButton());
        $this->addActionButton(new DeletePluginButton());
        $this->addMassActionButton(new MassDeletePluginButton());
    }

    public function customColumnHtmlEnable()
    {
        return (new EnablePluginSwitch())->getHtml();
    }

    public function replaceFieldDescription($key, $row)
    {
        return nl2br($row->description);
    }

    protected function loadData()
    {
        $ii = (new main\App\Models\CustomPlugin())->getTable();
        $query    = (new main\App\Models\CustomPlugin)
            ->query()
            ->getQuery()
            ->select("{$ii}.id", "{$ii}.name","{$ii}.description", "{$ii}.url", "{$ii}.version", "{$ii}.enable")
            ->groupBy("{$ii}.id");
        $dataProv = new main\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider();
        $dataProv->setDefaultSorting("name", 'ASC');
        $dataProv->setData($query);
        $this->setDataProvider($dataProv);
    }
}
