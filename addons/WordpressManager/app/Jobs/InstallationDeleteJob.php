<?php


namespace ModulesGarden\WordpressManager\App\Jobs;

use ModulesGarden\WordpressManager\App\Models\Installation;
use function \ModulesGarden\WordpressManager\Core\Helper\infoLog;

class InstallationDeleteJob extends BaseJob
{
    public function handle($text)
    {
        $this->installation = Installation::findOrFail($this->getModelData()['installationId']);
        try{
            if($this->installation->username)
            {
                $this->subModule()->setUsername($this->installation->username);
            }
            $this->subModule()->installation($this->getInstallation())->deleting([]);
        }catch (\Exception $ex){
            $this->log->error(sprintf('Installation delete cannot be completed. Installation #%s, Client ID #%s', $this->getInstallation()->id, $this->getInstallation()->user_id));
            $this->model->setStatus("failed");
            return false;
        }
        $this->subModule()->installationDelete($this->getInstallation()->relation_id, $this->getModelData()['delete']);
        $deletedInstallationInfo = $this->getInstallation()->getAttributes();

        $this->getInstallation()->delete();
        infoLog(sprintf('Installation has been deleted. Installation #%s, Client ID #%s, Domain #%s', $deletedInstallationInfo['id'], $deletedInstallationInfo['user_id'], $deletedInstallationInfo['domain']));

    }
}
