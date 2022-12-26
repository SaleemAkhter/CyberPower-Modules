<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;
use ModulesGarden\Servers\DirectAdminExtended\Core\Lang\Lang;

class SshKeyTable extends RawDataTableApi implements ClientArea
{
    use WhmcsParams;

    protected $id    = 'sshKeyTable';
    protected $name  = 'sshKeyTable';
    protected $title = "sshKeyTableTab";

    protected $info = false;

    protected function loadHtml()
    {
        $this->addColumn((new Column('keyid'))->setSearchable(true, Column::TYPE_STRING)->setOrderable('ASC'))
        ->addColumn((new Column('comment'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('fingerprint'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('type'))->setSearchable(false)->setOrderable(false))
        ->addColumn((new Column('size'))->setSearchable(false)->setOrderable(false));
    }

    public function initContent()
    {
        $this->addButton(new Buttons\Add())
        ->addButton(new Buttons\Paste())
        ->addMassActionButton(new Buttons\MassAction\Authorize())
            ->addMassActionButton(new Buttons\MassAction\Delete());
    }


    public function replaceFieldKeyid($key, $row)
    {
        $keyid=$row['keyid'];
        // $url=$this->getDownloadUrl($keyid);
        // debug($url);die();

        return '<a href="'.$this->getDownloadURL($row['id']).'">'.$keyid.'</a>';
    }
    public function getDownloadUrl($data)
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'SshKey',
            'mg-action'     => 'downloadKey',
            'keydata'=>$data
        ];

        return 'clientarea.php?'. \http_build_query($params);
    }
    protected function getURL()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'UserManager',
            'mg-action'     => 'EditUser',
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }
    protected function loadData()
    {

        $this->loadAdminApi();

        $result     = $this->adminApi->sshKey->admin_getKeys();
        $rows=[];
        foreach($result->public_keys as $keyname=>$item){


            $rows[]=[
                'id' =>base64_encode(json_encode(['keyname'=>$keyname,'data'=>$item->data,'fingerprint'=>$item->fingerprint,'comment'=>$item->comment])),
                'keyid' =>$keyname,
                'comment' =>$item->comment,
                'fingerprint'=>$item->fingerprint,
                'type'=>$item->type,
                'size'=>$item->keysize,

            ];
        }

        $result = $this->toArray($rows);
        $provider   = new ArrayDataProvider();
        $provider->setData($result);
        $provider->setDefaultSorting('keyid', 'ASC');
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
