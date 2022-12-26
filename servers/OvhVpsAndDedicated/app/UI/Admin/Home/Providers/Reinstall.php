<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Providers;

use Exception;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\WhmcsParams;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\OvhApiFactory;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Config;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Dedicated\Server;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\Ovh;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\CustomFields;



/**
 * Description of Rebuild
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Reinstall extends BaseDataProvider implements ClientArea, AdminArea
{
    use WhmcsParamsApp;
    use Lang;

    /**
     * @var Server
     */
    private $server;

    /**
     * @var Client
     */
    private $client;



    public function read()
    {

//        $vpsProduct = $this->getApi()->vps->one()->model();
//
//        $config = new Config($this->client->getProductID());
//        $config->getCategory();
//        $config->getProduct($vpsProduct->getOfferType());
//        $this->data['packageconfigoption_vpsProduct'] = $vpsProduct->getOfferType();
//        $this->availableValues['os'] = $config->getOS("vps_". $vpsProduct->getFullProductOffer());
//        $this->availableValues['distribution'] = $config->getDistributions(array_keys($this->availableValues['os'])[0]);
//        $this->availableValues['version'] = $config->getVpsDistributionVersion(array_keys($this->availableValues['distribution'])[0]);
//        $this->availableValues['language'] = $config->getVpsDistributionLanguages(array_keys($this->availableValues['version'])[0]);

    }

    public function create()
    {

    }

    public function delete()
    {

    }

    public function update()
    {
        try
        {
            $this->loadLang();

            if($this->getRequestValue('formData')['imageId'] === null)
            {
                return (new HtmlDataJsonResponse())->setMessage($this->lang->absoluteTranslate('reinstallVpsProvider', 'update', 'error', 'templateNotFound'))->setStatusError();
            }

            $serverName =  $this->getWhmcsParamByKey('customfields')['serverName'];
            $api = (new OvhApiFactory())->formParams();
            $request = [
                'doNotSendPassword' => false,
                'imageId'        => $this->getRequestValue('formData')['imageId'],
            ];
            $api->post(sprintf('/vps/%s/rebuild',$serverName),$request) ;

            return (new HtmlDataJsonResponse())->setStatusSuccess()
                    ->setMessage($this->lang->absoluteTranslate('reinstallVpsProvider', 'update', 'success', 'success'));
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    /**
     * @return Api
     */
    private function getApi()
    {
        $this->client = new Client($this->getAppWhmcsParams(WhmcsParams::getEssentialsKeys()));
        return Ovh::API($this->client);
    }
}
