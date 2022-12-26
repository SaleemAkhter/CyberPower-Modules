<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations;

use Carbon\Carbon;
use Exception;
use ModulesGarden\WordpressManager\App\Models\ModuleSettings;
use ModulesGarden\WordpressManager\App\Services\GooglePreviewAPI;
use ModulesGarden\WordpressManager\Core\Models\ExtendedEloquentModel;
use \ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Models\SpeedTest;
use ModulesGarden\WordpressManager\App\Models\WebsiteDetails;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AjaxElementInterface;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\WordpressManager\App\Models\SpeedTestTranslator;


use function ModulesGarden\WordpressManager\Core\Helper\sl;

class WebsiteDetailsPage extends BaseContainer implements ClientArea, AjaxElementInterface
{
    use main\App\Http\Client\BaseClientController;

    protected $id    = 'mg-website-details';
    protected $name  = 'mg-website-details-name';
    protected $title = 'mg-website-details-title';

    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-website-details';

    public function initContent()
    {
        $this->initSidebar();
    }

    private function initSidebar()
    {
        //main sidebar
        $sidebar = sl('sidebar');
        $sidebar->getSidebar('management')->setOrder(1);
        //actions
        $actions = new Sidebars\Actions('actions');
        $actions->setOrder(2);
        $sidebar->add($actions);
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
            $data['website']['screenshot'] = WebsiteDetails::where('wpid', $wpid)->value('screenshot');
            $data['WP_DEBUG']  = $this->subModule()->getWpCli($this->getInstallation())->option()->get('WP_DEBUG');
        } catch (\Exception $ex) { // WP_DEBUG does not exist
        }

        return (new RawDataJsonResponse(['data' => $data]));
    }

    private function getSpeedTestResult(int $wpid, string $strategy)
    {
        $speedTest = (WebsiteDetails::findOrFail($wpid))[$strategy];

        if(!$speedTest)
        {
            throw (new main\Core\HandlerError\Exceptions\Exception(404, "Data does not exist"));
        }

        if(!is_array($speedTest)) {
            $speedTest = json_decode($speedTest, true);
        }

        $speedTest = (new SpeedTest($speedTest))->toArray();

        return SpeedTestTranslator::T($speedTest);
    }
}
