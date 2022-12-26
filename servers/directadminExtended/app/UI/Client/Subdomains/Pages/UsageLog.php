<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Subdomains\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\Subdomain as Model;


class UsageLog extends RawDataTableApi implements ClientArea
{
    protected $id    = 'usageLogs';
    protected $name  = 'usageLogs';
    protected $title = 'usageLogsTab';

    protected function loadHtml()
    {
        $this->addColumn(
            (new Column('logs'))
                //->addSearchableName('name')
                ->setSearchable(true, Column::TYPE_STRING)
                ->setOrderable('DESC')
        );
    }

    public function initContent()
    {

    }

    protected function loadData()
    {
        $subdomain = json_decode(base64_decode($this->getRequestValue('subdomain')));
        $subdomainPrefix = explode($subdomain->domain, $subdomain->name);

        $data = [
            'domain' => $subdomain->domain,
            'type' => 'log',
            'subdomain' => rtrim($subdomainPrefix[0], '.')
        ];

        $this->loadUserApi();

        $result = $this->userApi->subdomain->getLogs(new Model($data));

        $provider   = new ArrayDataProvider();
        $provider->setData($this->toArray($result->getLogs()));
        $provider->setDefaultSorting('type', 'ASC');

        $this->setDataProvider($provider);
    }

    private function toArray($result)
    {
        $resultArray = [];
        foreach($result as $keyRow => $row)
        {
            $resultArray[$keyRow]['logs'] = $row;
        }
        return $resultArray;
    }
}