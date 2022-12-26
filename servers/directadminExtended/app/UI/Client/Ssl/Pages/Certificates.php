<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\ErrorHandler\Exceptions\ApiException;
use Carbon\Carbon;
class Certificates extends RawDataTableApi implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;

    protected $id    = 'certificatesTable';
    protected $name  = 'certificatesTable';
    protected $title = 'certificatesTableTitle';
    protected $searchBarButtonsVisible = 2;

    protected function loadHtml()
    {
        $this->addColumn((new Column('domain'))->setSearchable(true)->setOrderable('ASC'));
    }

    public function initContent()
    {

        $this->addButtonToDropdown(new Buttons\Upload());

        if ($this->isFeatureEnabled('ssl_allow_encrypt'))
        {
            $this->addButton(new Buttons\LetsEncrypt());
//            $this->addButtonToDropdown(new Buttons\LetsEncrypt());
        }

        $this->addButton(new Buttons\Generate())
            ->addActionButton(new Buttons\ViewCertificate());
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
                if(empty($response['certificate']))
                {
                    continue;
                }
                $response['domain'] = $domain;

                /**
                 * fix before http code 414 (uri too long)
                 */
                $data = [
                    'certificate' => $response['certificate']
                ];
                if(isset($response['Not_After'])){
                    $expdate = Carbon::createFromFormat("M d H:i:s Y e",$response['Not_After']);
                    $data['Not_After']=$response['Not_After'];
                    $data['expiryDays']=$response['expiryDays']= $expdate->diffInDays(Carbon::now());
                    $renewaldate = Carbon::createFromFormat("M d H:i:s Y e",$response['Not_Before']);

                    $data['Not_Before']=$response['Not_Before'];
                    $data['renewalDays']=$response['LETSENCRYPT_RENEWAL_DAYS'];
                }

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
