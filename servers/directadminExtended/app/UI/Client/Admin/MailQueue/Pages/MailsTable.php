<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\MailQueue\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;
use ModulesGarden\Servers\DirectAdminExtended\Core\Lang\Lang;

class MailsTable extends RawDataTableApi implements ClientArea
{
    use WhmcsParams;

    protected $id    = 'mailsTable';
    protected $name  = 'mailsTable';
    protected $title = "mailsTableTab";

    protected $info = false;

    protected function loadHtml()
    {
        $this->addColumn((new Column('id'))->setSearchable(false)->setOrderable('ASC'))
        ->addColumn((new Column('time'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('size'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('sender'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('recepient'))->setSearchable(false)->setOrderable(false));
    }

    public function initContent()
    {
        // $this->addButton(new Buttons\Refresh());
    }



    protected function loadData()
    {

        $this->loadAdminApi();

        $result     = $this->adminApi->mailQueue->admin_getProcesses();
        $rows=[];
        $total=null;
        foreach($result->processes as $key=>$process){
            if($key=='info'){
                $total=$process->rows;
                continue;
            }
            $rows[]=[
                'pid' =>$process->PID,
                'user' =>$process->USER,
                'pr' =>$process->PR,
                'ni'=>$process->NI,
                'virt'=>$process->VIRT,
                'res'=>$process->RES,
                'shr'=>$process->SHR,
                's'=>$process->s,
                'cpupercent'=>$process->{'%CPU'},
                'mempercent'=>$process->{'%MEM'},
                'time'=>$process->{'TIME+'},
                'command'=>$process->COMMAND,
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
