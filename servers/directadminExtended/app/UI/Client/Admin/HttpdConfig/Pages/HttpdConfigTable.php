<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\HttpdConfig\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\HttpdConfig\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;
use ModulesGarden\Servers\DirectAdminExtended\Core\Lang\Lang;


class HttpdConfigTable extends DataTableApi implements ClientArea
{
    use WhmcsParams;

    protected $id    = 'httpdConfTable';
    protected $name  = 'httpdConfTable';
    protected $title = null;

    protected $info = false;

    protected function loadHtml()
    {
        $this->addColumn(
            (new Column('domain'))
                //->addSearchableName('name')
                ->setSearchable(true, Column::TYPE_STRING)
                ->setOrderable('ASC')
        )
        ->addColumn((new Column('user'))->setSearchable(true)->setOrderable(true))
        ->addColumn((new Column('configfile'))->setSearchable(true)->setOrderable(true));
    }

    public function initContent()
    {
    }
    public function getEditButton()
    {
        $button = new ButtonRedirect('editButton');

        $button->setRawUrl($this->getURL())
            ->setIcon('lu-btn__icon lu-zmdi lu-zmdi-edit')
            ->setRedirectParams(['actionElementId'=>':id']);

        return $button;
    }


    protected function getHttpdConfUrl($domain)
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'HttpdConfig',
            'mg-action'     => 'httpdconf',
            'actionElementId'=>$domain
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }
    protected function getNginxUrl($domain)
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'HttpdConfig',
            'mg-action'     => 'nginx',
            'actionElementId'=>$domain
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }
    protected function getphpfpmfUrl($domain)
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'HttpdConfig',
            'mg-action'     => 'phpfpm',
            'actionElementId'=>$domain
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }



    public function replaceFieldConfigfile($key, $row)
    {
        $domains='<a href="'.$this->getHttpdConfUrl($row['domain']).'">httpd.conf</a> <a href="'.$this->getNginxUrl($row['domain']).'">nginx.conf proxy</a> <a href="'.$this->getphpfpmfUrl($row['domain']).'">php-fpm.conf (7.4)</a>';

        return $domains;

    }


    protected function loadData()
    {

        $this->loadAdminApi();

        $result     = $this->adminApi->httpdConfig->get();
        $users=[];
        foreach($result->domains as $key=>$domain){
            if($key=='info'){
                continue;
            }
            $users[$key]['id']= $domain->domain;
            $users[$key]['configfile']=[
                $domain->bandwidth
            ];
            $users[$key]['domain']=$domain->domain;
            $users[$key]['user']=$domain->info->user;
        }
        $result = json_decode(json_encode($users),true);
        $provider   = new ArrayDataProvider();
        $provider->setData($result);
        $provider->setDefaultSorting('domain', 'ASC');
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
    protected function bytesToHuman($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $bytes > 1024; $i++) $bytes /= 1024;
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
