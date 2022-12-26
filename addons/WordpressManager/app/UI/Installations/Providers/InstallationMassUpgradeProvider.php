<?php


namespace ModulesGarden\WordpressManager\App\UI\Installations\Providers;


use ModulesGarden\WordpressManager\App\Jobs\InstallationUpgradeJob;
use ModulesGarden\WordpressManager\App\Models\Installation;
use function ModulesGarden\WordpressManager\Core\Helper\queue;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;

class InstallationMassUpgradeProvider extends BaseModelDataProvider implements ClientArea
{
    public function __construct()
    {
        parent::__construct( new Installation());
    }

    public function upgradeMass(){

        $backup = $this->getRequestValue('formData')['backup'] =="on" ? 1: 0;
        foreach ($this->getRequestValue('massActions') as $id)
        {
            if(!Installation::ofId($id)->ofUserId($this->request->getSession('uid'))->count()){
                throw  new \Exception("Access denied");
            }
            queue(InstallationUpgradeJob::class,[ 'installationId' => $id, "backup" => $backup ]);
        }
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Upgrading the installations have been starded, please wait, as the process may take up few minutes.');
    }

}