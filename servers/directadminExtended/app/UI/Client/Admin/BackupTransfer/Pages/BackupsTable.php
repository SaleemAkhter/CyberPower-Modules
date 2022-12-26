<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;
use ModulesGarden\Servers\DirectAdminExtended\Core\Lang\Lang;
class BackupsTable extends RawDataTableApi implements ClientArea
{
    use WhmcsParams;

    protected $id    = 'backupsTable';
    protected $name  = 'backupsTable';
    protected $title = "backupsTableTab";

    protected $info = false;

    protected function loadHtml()
    {
        $this->addColumn((new Column('when'))->setSearchable(false)->setOrderable('ASC'))
        ->addColumn((new Column('who'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('where'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('what'))->setSearchable(false)->setOrderable(false));
    }

    public function initContent()
    {
        $this->addButtonToDropdown($this->getScheduleButton())
        ->addButtonToDropdown($this->getRestoreButton())
        ->addButtonToDropdown($this->getBackupCreateButton())
        ->addButtonToDropdown(new Buttons\BackupSetting())
        ->addActionButton($this->getEditButton())
        ->addMassActionButton(new Buttons\MassAction\Run())
        ->addMassActionButton(new Buttons\MassAction\Duplicate())
        ->addMassActionButton(new Buttons\MassAction\Delete());
    }
    public function getEditButton()
    {
        $button = new ButtonRedirect('editButton');

        $button->setRawUrl($this->getURL())
            ->setIcon('lu-btn__icon lu-zmdi lu-zmdi-edit')
            ->setRedirectParams(['actionElementId'=>':id']);

        return $button;
    }
    public function getScheduleButton()
    {
        $button = new ButtonRedirect('scheduleButton');

        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'BackupTransfer',
            'mg-action'     => 'schedule',
        ];

        $url= 'clientarea.php?'. \http_build_query($params);


        $button->setRawUrl($url)
        ->setIcon('')
            ->setShowTitle()
            ->replaceClasses(['lu-btn', 'lu-btn--primary']);

        return $button;
    }
     public function getRestoreButton()
    {
        $button = new ButtonRedirect('restoreButton');
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'BackupTransfer',
            'mg-action'     => 'restore',
        ];

        $url= 'clientarea.php?'. \http_build_query($params);
        $button->setRawUrl($url)
        ->setIcon('')
            ->setShowTitle()
            ->replaceClasses(['lu-btn', 'lu-btn--primary']);

        return $button;
    }
    public function getBackupCreateButton()
    {
        $button = new ButtonRedirect('backupButton');
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'BackupTransfer',
            'mg-action'     => 'backup',
        ];

        $url= 'clientarea.php?'. \http_build_query($params);
        $button->setRawUrl($url)
            ->setIcon('')
            ->setShowTitle()
            ->replaceClasses(['lu-btn', 'lu-btn--primary'])
            ->setTitle('backup');

        return $button;
    }
    protected function getURL()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'BackupTransfer',
            'mg-action'     => 'edit',
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }
    public function replaceFieldwhen($key, $row)
    {
        return '<ul class="fx:dir:column cronul">
            <li> Minute: '.$row['when']->minute.'</li>
            <li> Hour: '.$row['when']->hour.'</li>
            <li> Day of Month: '.$row['when']->dayofmonth.'</li>
            <li> Month: '.$row['when']->month.'</li>
            <li> Day of Week: '.$row['when']->dayofweek.'</li>
        </ul>';
    }
    public function replaceFieldwho($key, $row)
    {
        $response='';

        if($row['who']->who=='all'){
            $response="All Users";
        }elseif($row['who']->who=='selected'){
            $response='<strong>Selected Users: </strong><ul>';
            foreach ($row['who']->select as $key => $user) {
                $response.='<li>'.$user.'</li>';
            }
            $response.='</ul>';
        }
        return $response;

    }
    public function replaceFieldwhere($key, $row)
    {
        if(isset($row['where']->path)){
            return $row['where']->path;
        }
    }
    public function replaceFieldwhat($key, $row)
    {
        $response='';
        if(isset($row['what']->what)){
            if($row['what']->what=="select"){
                $response='<ul>';
                foreach ($row['what']->select as $key => $k) {
                    $response.='<li>'.ucfirst($k).' Directory</li>';
                }
                $response.='</ul>';

            }elseif($row['what']->what=="all"){
                $response="All Data";
            }

        }
        return $response;
    }


    protected function loadData()
    {

        $this->loadAdminApi();

        $result     = $this->adminApi->backupTransfer->admin_getStep('who');
        $rows=[];
        foreach($result->crons as $key=>$cron){
            if($key=='info'){
                continue;
            }
            $rows[]=[
                'id' =>$cron->id,
                'when' =>$cron->when,
                'who' =>$cron->who,
                'what'=>$cron->what,
                'where'=>$cron->where,
            ];
        }

        $result = $this->toArray($rows);
        $provider   = new ArrayDataProvider();
        $provider->setData($result);
        $provider->setDefaultSorting('id', 'ASC');
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
