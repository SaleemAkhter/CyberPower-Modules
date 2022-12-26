<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ServiceMonitor\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;
use ModulesGarden\Servers\DirectAdminExtended\Core\Lang\Lang;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ServiceMonitor\Buttons;


class ServicesTable extends RawDataTableApi implements ClientArea
{
    use WhmcsParams;

    protected $id    = 'servicesTable';
    protected $name  = 'servicesTable';
    protected $title = "servicesTableTab";

    protected $info = false;

    protected function loadHtml()
    {
        $this->addColumn((new Column('service'))->setSearchable(false)->setOrderable('ASC'))
        ->addColumn((new Column('status'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('pid'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('memoryusage'))->setSearchable(false)->setOrderable(false));
    }

    public function initContent()
    {
        // $this->addButton(new Buttons\Refresh());
        $this->addActionButtonToDropdown(new Buttons\Start())
            ->addActionButtonToDropdown(new Buttons\Stop())
                ->addActionButtonToDropdown(new Buttons\Restart())
                ->addActionButtonToDropdown(new Buttons\Reload());
    }



    protected function loadData()
    {

        $this->loadAdminApi();

        $result     = $this->adminApi->serviceMonitor->admin_getServices();
        // debug($result);die();
        $rows=[];
        $total=null;
        foreach($result->status as $service=>$status){

            $rows[]=[
                'id' =>$service,
                'service' =>$service,
                'status' =>$status,
                'pid' =>$result->pids->{$service},
                'memoryusage'=>$result->memory->{$service},
                'actions'=>$result->actions->{$service},
                'restart'=>(in_array('restart',$result->actions->{$service}))?"Yes":"No",
                'canreload'=>(in_array('reload',$result->actions->{$service}))? true: false,
                'canstart'=>(is_null($result->pids->{$service}))?"Yes":"no",
                'canstop'=>(!is_null($result->pids->{$service}))?"Yes":"no",
            ];
        }

        $result = $this->toArray($rows);
        $provider   = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('pid', 'ASC');
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
    protected function replaceFieldPid($key, $row)
    {
        if(is_array($row[$key])){
            return implode(" ",$row[$key]);
        }

        return $row[$key];
    }
    protected function replaceFieldMemoryusage($key, $row)
    {
        return $this->bytesToHuman($row[$key]);
    }
    protected function bytesToHuman($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $bytes > 1024; $i++) $bytes /= 1024;
        return round($bytes, 2) . ' ' . $units[$i];
    }
    protected function replaceFieldStatus($key, $row)
    {
        if($row[$key]=="on")
        {
            return '<span data-v-62e0f0f6="" class="badge disp:iblock radius:def border:a:def txt:medium wrap:nowrap -theme-safe -size-normal -align-start"><div class="badge-inner fx:dir:row fx:cross:stretch"><div class="fx:dir:row fx:cross:center fx:main:start fx:wrap:false fxi:grow:true" layout-gutter="0.5" style="margin-top: -0.5rem; margin-left: -0.5rem;"><span class="icon" style="margin-top: 0.5rem; margin-left: 0.5rem;"><svg version="1.1" viewBox="0 0 510 510" class="svg-icon svg-fill c:txt:safe" style="width: 14px; height: 14px;"><path fill="currentColor" _stroke="none" pid="0" d="M255 0C114.75 0 0 114.75 0 255s114.75 255 255 255 255-114.75 255-255S395.25 0 255 0zm-51 382.5L76.5 255l35.7-35.7 91.8 91.8 193.8-193.8 35.7 35.7L204 382.5z"></path></svg></span> <span class="badge-text type:content lineh:1" style="margin-top: 0.5rem; margin-left: 0.5rem;">'.di(Lang::class)->absoluteTranslate('Running').'</span></div></div></span>';
        }else{
            return '<span data-v-62e0f0f6="" class="badge disp:iblock radius:def border:a:def txt:medium wrap:nowrap -theme-danger -size-normal -align-start"><div class="badge-inner fx:dir:row fx:cross:stretch"><div class="fx:dir:row fx:cross:center fx:main:start fx:wrap:false fxi:grow:true" layout-gutter="0.5" style="margin-top: -0.5rem; margin-left: -0.5rem;"><span class="icon" style="margin-top: 0.5rem; margin-left: 0.5rem;"><svg version="1.1" viewBox="0 0 246.027 246.027" class="svg-icon svg-fill c:txt:danger" style="width: 14px; height: 14px;"><path fill="currentColor" _stroke="none" pid="0" d="M242.751 196.508l-98.814-171.15c-4.367-7.564-12.189-12.081-20.924-12.081s-16.557 4.516-20.924 12.081L3.276 196.508c-4.368 7.564-4.368 16.596 0 24.161S15.465 232.75 24.2 232.75h197.629c8.734 0 16.556-4.516 20.923-12.08 4.367-7.565 4.366-16.597-.001-24.162zm-119.737 8.398c-8.672 0-15.727-7.055-15.727-15.727 0-8.671 7.055-15.726 15.727-15.726s15.727 7.055 15.727 15.726c-.001 8.673-7.056 15.727-15.727 15.727zm15.833-67.226c0 8.73-7.103 15.833-15.833 15.833s-15.833-7.103-15.833-15.833V65.013a7.5 7.5 0 0 1 7.5-7.5h16.667a7.5 7.5 0 0 1 7.5 7.5v72.667z"></path></svg></span> <span class="badge-text type:content lineh:1" style="margin-top: 0.5rem; margin-left: 0.5rem;">'.di(Lang::class)->absoluteTranslate('Stopped').'</span></div></div></span>';
        }

        return di(Lang::class)->absoluteTranslate('unlimited');
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
