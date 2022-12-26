<?php


namespace ModulesGarden\WordpressManager\App\Jobs;

use \ModulesGarden\WordpressManager\Core\Queue\Job;
use \ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use \ModulesGarden\WordpressManager\App\Models\Installation;
use function \ModulesGarden\WordpressManager\Core\Helper\infoLog;

class MultisiteDisableJob extends Job
{
    use BaseClientController;
    public function handle($text)
    {
        if(!$this->findInstallation()){
            $this->sleep(5);
            return false;
        }
        if($this->installation->hosting->domainstatus != 'Active'){
            return true;
        }
        $config =  $this->subModule()->getConfig($this->getInstallation());
        $config->set('MULTISITE', '0', 'constant');
        $config->set('WP_ALLOW_MULTISITE', '0', 'constant');
        infoLog(sprintf('Installation Multisite has been disabled, Instalation #%s, Client ID #%s', $this->getInstallation()->id, $this->getInstallation()->user_id));
    }
    
    /**
     * 
     * @return Installation|null
     */
    private function findInstallation(){
        $data = unserialize(  $this->model->data);
        if($data['installationId']){
            $this->installation = Installation::where("id", $data['installationId'])
                                              ->first();
            return $this->installation;
        }
        $this->installation = Installation::ofHostingId($data['hostingId'])
                                ->ofDomain($data['domain'])
                                ->ofPath($data['softpath'])
                                ->first();
        return $this->installation;
    }
    
    private function sleep($minutes =1){
        $this->model->setWaiting();
        $this->model->setRetryAfter(date("Y-m-d H:i:s", strtotime("+{$minutes} minute")));
        $this->model->increaseRetryCount(); 
    }
  
}
