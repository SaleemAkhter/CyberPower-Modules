<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Providers;

use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use ModulesGarden\WordpressManager\App\Models\SpeedTest;
use ModulesGarden\WordpressManager\App\Models\SpeedTestTranslator;
use ModulesGarden\WordpressManager\App\Models\WebsiteDetails;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\RawDataJsonResponse;

/**
 * Description of InstallationProvider
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class WebsiteDetailsProvider extends BaseDataProvider implements ClientArea
{
    use BaseClientController;

    public function read()
    {
        $this->returnAjaxData();
    }

    public function update()
    {
    }

    private function getSpeedTestResult(int $wpid, string $strategy)
    {
        $speedTest = (WebsiteDetails::findOrFail($wpid))[$strategy];
        $speedTest = (new SpeedTest($speedTest))->toArray();

        return SpeedTestTranslator::T($speedTest);
    }

    public function returnAjaxData()
    {   
        $this->loadRequestObj();

        $data = [];
        $wpid = $this->request->get('wpid');

        $this->reset();
        $this->setInstallationId($this->request->get('wpid'))
            ->setUserId($this->request->getSession('uid'));
        //Instalation Details
        if ($this->getInstallation()->username) {
            $this->subModule()->setUsername($this->getInstallation()->username);
        }

        $data['details'] =  $this->subModule()->installation($this->getInstallation())->read();

        $data['details']['userins']['site_name'] =  html_entity_decode($data['details']['userins']['site_name'], ENT_QUOTES);

        if (!$data['details']['userins']['site_name'] && !$data['details']['userins']['live_ins']['site_name']) {
            $data['details']['userins']['site_name'] = "-";
        }

        try {
            $data['website'] = $this->getSpeedTestResult($wpid, 'desktop');

            $data['WP_DEBUG']  = $this->subModule()->getWpCli($this->getInstallation())->option()->get('WP_DEBUG');
        } catch (\Exception $ex) { // WP_DEBUG does not exist
        }

        return (new RawDataJsonResponse(['data' => $data]));
    }
}
