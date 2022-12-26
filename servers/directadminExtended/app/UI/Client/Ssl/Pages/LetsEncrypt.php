<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\ErrorHandler\Exceptions\ApiException;

class LetsEncrypt extends BaseStandaloneForm implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;

    protected $id    = 'letsEncrypt';
    protected $name  = 'letsEncrypt';
    protected $title = 'letsEncryptTitle';
    protected $searchBarButtonsVisible = 2;



    public function initContent()
    {

//         $this->addButtonToDropdown(new Buttons\Upload());

//         if ($this->isFeatureEnabled('ssl_allow_encrypt'))
//         {
//             $this->addButton(new Buttons\LetsEncrypt());
// //            $this->addButtonToDropdown(new Buttons\LetsEncrypt());
//         }

//         $this->addButton(new Buttons\Generate())
//             ->addActionButton(new Buttons\ViewCertificate());
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
