<?php


namespace ModulesGarden\WordpressManager\App\Jobs;

use ModulesGarden\WordpressManager\App\Models\Installation;
use function \ModulesGarden\WordpressManager\Core\Helper\infoLog;

class InstallationUpgradeJob extends BaseJob
{
    public function handle($text)
    {
        $this->installation = Installation::findOrFail($this->getModelData()['installationId']);
        if($this->getModelData()['backup'] && !$this->getModelData()['isBackupCreated']){
            $data = [
                'installationId'  => $this->getInstallation()->relation_id,
                'backupDirectory' => 1,
                'backupDataDir'   => 1,
                'backupDatabase'  => 1,
                'note'            => 'Backup before installation update'
            ];
            $this->subModule()->backupCreate($data);
            $this->putModelDataAndSave(['isBackupCreated' => true]);
            $this->sleep(1);
        }
        try{
            $details =  $this->subModule()->installation($this->getInstallation())->upgrade([]);
        }catch (\Exception $ex){
            $this->log->error(sprintf('Installation update is not available. there is the latest WordPress, version: %s, Instalation #%s, Client ID #%s',$this->getInstallation()->version , $this->getInstallation()->id, $this->getInstallation()->user_id));
            $this->model->setStatus("failed");
            return false;
        }
        $this->subModule()->installationUpgrade($this->getInstallation()->relation_id);
        infoLog(sprintf('Installation has been upgraded to version: %s, Instalation #%s, Client ID #%s',$details['software']['ver'], $this->getInstallation()->id, $this->getInstallation()->user_id));

    }
}
