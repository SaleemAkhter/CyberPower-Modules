<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\ErrorHandler\Exceptions\ApiException;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;
use ModulesGarden\Servers\DirectAdminExtended\Core\Lang\Lang;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class AccountsTable extends DataTableApi implements ClientArea
{
    protected $id    = 'accountTable';
    protected $name  = 'accountTable';
    protected $title = null;

    protected function loadHtml()
    {
        $this->addColumn((new Column('email'))->setSearchable(true)->setOrderable('ASC'))
                ->addColumn((new Column('usage'))->setSearchable(true)->setOrderable())
                ->addColumn((new Column('sent'))->setSearchable(true)->setOrderable());
    }

    public function initContent()
    {
        $this->addButton(new Buttons\Add())
            ->addActionButton(new Buttons\EditPassword())
                ->addActionButton(new Buttons\EditLimits())
                ->addActionButton(new Buttons\Delete())
        ->addMassActionButton(new Buttons\MassAction\Delete());
    }

    public function replaceFieldUsage($key, $row)
    {
        if($row['quota'] != null)
        {
            return $row['usage'] . ' / ' . $this->formatLimitValue($row['quota']);
        }
        return $row['usage'];

    }

    public function replaceFieldSent($key, $row)
    {
        if($row['sent'] != null)
        {
            return $row['sent'] . ' / ' . $this->formatLimitValue($row['limit']);
        }

    }

    protected function loadData()
    {
        $this->loadUserApi();

        $result = [];
        $iter = 0;

        foreach ($this->getDomainList() as $domain) {
            $data = [
                'domain' => $domain
            ];

            try
            {
                $response = $this->userApi->email->lists(new Models\Command\Email($data));
            }
            catch(ApiException $exc)
            {
                 continue;
            }

            foreach($response->emails as $elem => $each)
            {
                if(is_numeric($elem))
                {
                    $array = [];
                    $array['domain'] = $domain;
                    $array['usage'] = $this->toMegabyte($each->usage->apparent_usage);
                    $array['quota'] = $this->toMegabyte($each->usage->quota);
                    $array['limit'] = $each->sent->send_limit;
                    $array['sent'] = $each->sent->sent;
                    $array['globalLimit'] = $response->GLOBAL_PER_EMAIL_LIMIT;

                    if(empty(explode('@', $each->login, - 1)))
                    {
                        $array['email'] = $each->login . '@' . $domain;
                        $array['sent'] = null;
                        $array['main'] = true;
                    }
                    else
                    {
                        $array['email'] = $each->login;
                        $array['main'] = false;
                    }
                    $array['id'] = base64_encode(json_encode($array));
                    $result[$iter] = $array;
                    $iter++;
                }
            }
        }

        $provider = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('id', 'ASC');
        
        $this->setDataProvider($provider);
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
