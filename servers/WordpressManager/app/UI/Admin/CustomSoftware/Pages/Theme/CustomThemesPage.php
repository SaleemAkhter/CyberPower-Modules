<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Pages\Theme;

use ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\EnableThemeSwitch;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Buttons\Theme\CreateThemeButton;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Buttons\Theme\UpdateThemeButton;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Buttons\Theme\DeleteThemeButton;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Buttons\Theme\MassDeleteThemeButton;
use \ModulesGarden\WordpressManager as main;

class CustomThemesPage extends DataTable implements AdminArea
{
    protected $id    = 'customThemesPage';
    protected $name  = 'customThemesPage';
    protected $title = null;

    use RequestObjectHandler;

    protected function loadHtml()
    {
        $this->initIds('customThemesDataTable');
        $this->title = null;
        $ii = (new main\App\Models\CustomTheme())->getTable();
        $this->addColumn((new Column('name', $ii))->setSearchable(true, 'string')->setOrderable('ASC'))
            ->addColumn((new Column('description',  $ii))->setSearchable(true, 'string'))
            ->addColumn((new Column('version',  $ii))->setSearchable(true, 'string'))
            ->addColumn((new Column('enable',  $ii))->setSearchable(true, 'string'));
    }

    public function initContent()
    {
        $this->addButton(new CreateThemeButton());
        $this->addActionButton(new UpdateThemeButton());
        $this->addActionButton(new DeleteThemeButton());
        $this->addMassActionButton(new MassDeleteThemeButton());
    }

    public function customColumnHtmlEnable()
    {
        return (new EnableThemeSwitch())->getHtml();
    }

    public function replaceFieldDescription($key, $row)
    {
        return nl2br($row->description);
    }

    protected function loadData()
    {
        $ii = (new main\App\Models\CustomTheme())->getTable();
        $query    = (new main\App\Models\CustomTheme)
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
