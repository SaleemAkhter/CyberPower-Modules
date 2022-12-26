<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;
use ModulesGarden\Servers\DirectAdminExtended\Core\Lang\Lang;

class PluginsTable extends RawDataTableApi implements ClientArea
{
    use WhmcsParams;

    protected $id    = 'processTable';
    protected $name  = 'processTable';
    protected $title = "processTableTab";

    protected $info = false;

    protected function loadHtml()
    {
        $this->addColumn((new Column('plugin'))->setSearchable(false)->setOrderable('ASC'))
        ->addColumn((new Column('version'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('availableversion'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('active'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('canupdate'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('installed'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('author'))->setSearchable(false)->setOrderable(false));
    }

    public function initContent()
    {
        // ->addButton(new Buttons\Add())
        $this->addActionButtonToDropdown(new Buttons\Deactivate())
            ->addActionButtonToDropdown(new Buttons\Activate())
            ->addActionButtonToDropdown(new Buttons\Update())
            ->addActionButtonToDropdown(new Buttons\Install())
            ->addActionButtonToDropdown(new Buttons\UnInstall())
            ->addActionButtonToDropdown(new Buttons\Delete());
    }



    protected function loadData()
    {

        $this->loadAdminApi();

        $result     = $this->adminApi->pluginManager->list();
        $rows=[];
        $total=null;
        foreach($result as $pluginname=>$plugin){
            $rows[]=[
                'id'=>$plugin->id,
                'plugin' =>$pluginname,
                'version' =>$plugin->version,
                'availableversion' =>$plugin->available_version,
                'active'=>$plugin->active,
                'canupdate'=>($plugin->available_version)?"Yes":"No",
                'installed'=>$plugin->installed,
                'showactivatebtn'=>($plugin->installed=="yes" && $plugin->active!='yes')?"yes":"no",
                'update_url'=>$plugin->update_url,
                'author'=>$plugin->author
            ];
        }

        $result = $this->toArray($rows);
        $provider   = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('plugin', 'ASC');
        $this->setDataProvider($provider);
    }

    private function toArray($result)
    {
        $resultArray = [];
        foreach($result as $keyRow => $row)
        {
            if(is_object($row))
            {
                foreach($row as $key => $value)
                {
                    $resultArray[$keyRow][$key] = $value;
                }

                continue;
            }
            $resultArray[$keyRow] = $row;
        }

        return $resultArray;
    }
    protected function formatLimitValue($value)
    {
        if((int) $value > 0 || empty($value))
        {
            return $value;
        }

        return di(Lang::class)->absoluteTranslate('unlimited');
    }

    protected function toMegabyte($number)
    {
        if(is_numeric($number))
        {
            return round($number / 1024 / 1024, 3);
        }
        return $number;
    }
}
