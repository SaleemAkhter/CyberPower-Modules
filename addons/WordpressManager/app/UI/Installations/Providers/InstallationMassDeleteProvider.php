<?php


namespace ModulesGarden\WordpressManager\App\UI\Installations\Providers;

use ModulesGarden\WordpressManager\App\Jobs\InstallationDeleteJob;
use ModulesGarden\WordpressManager\App\Models\Installation;
use function ModulesGarden\WordpressManager\Core\Helper\queue;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;

class InstallationMassDeleteProvider extends BaseModelDataProvider implements ClientArea
{
    public function __construct()
    {
        parent::__construct( new Installation());
    }

    public function deleteMass(){
        $form = [
            'directoryDelete' => $this->getRequestValue('formData')['directoryDelete'],
            'databaseDelete' => $this->getRequestValue('formData')['databaseDelete'],
        ];
        foreach ($this->getRequestValue('massActions') as $id)
        {
            if(!Installation::ofId($id)->ofUserId($this->request->getSession('uid'))->count()){
                throw  new \Exception("Access denied");
            }
            queue(InstallationDeleteJob::class,[ 'installationId' => $id, "delete" => $form ]);
        }
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Deleting the installations have been starded, please wait, as the process may take up few minutes.');
    }

}