<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SiteSummary\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;


class SiteSummary extends DataTableApi implements ClientArea
{
    use Lang;

    protected $id    = 'siteSummary';
    protected $name  = 'siteSummary';
    protected $title = null;

    protected function loadHtml()
    {
        $this->addColumn((new Column('setting'))->setSearchable(true)->setOrderable()->setOrderable('ASC'))
                ->addColumn((new Column('usage'))->setSearchable(true))
                ->addColumn((new Column('config'))->setSearchable(true));
    }

    protected function loadData()
    {
        $this->loadLang();
        $this->loadUserApi();
        $result = [];
        $data   = [
            'username' => $this->getWhmcsParamByKey('username')
        ];
        $usage  = $this->userApi->account->usage(new Models\Command\Account($data));
        $config = $this->userApi->account->config(new Models\Command\Account($data));
        
        //convert to MB
        if(isset($usage['db_quota']))
        {
            $usage['db_quota'] = number_format($usage['db_quota']/(1024*1024) ,4); 
        }
        if(isset($usage['email_quota']))
        {
            $usage['email_quota'] = number_format($usage['email_quota']/(1024*1024) ,4); 
        }
        
        foreach ($usage as $option => $value)
        {
            if (isset($config[$option]))
            {
                $array = [];
                $array['setting'] = $this->lang->translate($option);
                $array['usage']   = $value;
                $array['config']  = $config[$option] === 'unlimited' ? $this->lang->translate('unlimited') : $config[$option];

                $result[] = $array;
            }
        }
        $additionalUsageDetails = ['email_quota','db_quota','email_deliveries_outgoing','email_deliveries_incoming'];
        foreach ($additionalUsageDetails as $detail)
        {
            if (isset($usage[$detail]))
            {

                $array = [];
                $array['setting'] = $this->lang->translate($detail);
                $array['usage']   = $usage[$detail];

                $result[] = $array;
            }
        }

        $additionalConfigDetails = ['email','ip','name','language'];
        foreach ($additionalConfigDetails as $detail)
        {
            if (isset($config[$detail]))
            {
                $array = [];
                $array['setting'] = $this->lang->translate($detail);
                $array['usage']   = $config[$detail];

                $result[] = $array;
            }
        }

        $provider   = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('setting', 'ASC');
        
        $this->setDataProvider($provider);
    }
}
