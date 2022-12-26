<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\ErrorHandler\Exceptions\ApiException;

class PrivateKeys extends RawDataTableApi implements ClientArea
{
    protected $id    = 'privateKeysTable';
    protected $name  = 'privateKeysTable';
    protected $title = 'privateKeysTableTitle';

    protected function loadHtml()
    {
        $this->addColumn((new Column('domain'))->setSearchable(true)->setOrderable('ASC'));
    }

    public function initContent()
    {
        $this->addActionButton(new Buttons\ViewKey());
    }

    protected function loadData()
    {
        $this->loadUserApi();
        $result = [];
        foreach ($this->getDomainList() as $domain)
        {
            $data     = [
                'domain' => $domain
            ];

            try 
            {
                $response = $this->userApi->ssl->lists(new Models\Command\Ssl($data))->firstOfArray();
            }
            catch(ApiException $ex)
            {
                continue;
            }
            
            if ($response)
            {

                if(empty($response['key']))
                {
                    continue;
                }
                $response['domain'] = $domain;

                /**
                 * fix before http code 414 (uri too long)
                 */
                $data = [
                    'key' => $response['key']
                ];

                $response['allData'] = base64_encode(json_encode($data));
            }
            $result[] = $response;
        }

        $dataProvider = new ArrayDataProvider();

        $dataProvider->setData($result);
        $dataProvider->setDefaultSorting('domain', 'ASC');

        $this->setDataProvider($dataProvider);
    }

}
