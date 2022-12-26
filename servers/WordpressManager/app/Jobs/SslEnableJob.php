<?php


namespace ModulesGarden\WordpressManager\App\Jobs;

use \ModulesGarden\WordpressManager\Core\Queue\Job;
use \ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use \ModulesGarden\WordpressManager\App\Models\Installation;
use function \ModulesGarden\WordpressManager\Core\Helper\infoLog;

class SslEnableJob extends Job
{
    use BaseClientController;
    public function handle($text)
    {
        if(!$this->findInstallation()){
            $this->sleep(5);
            return false;
        }
        if($this->installation->hosting->domainstatus != 'Active' || $this->installation->isHttps()){
            return true;
        }
        $this->subModule()->ssl()->setInstallation($this->installation)->on();
        $this->getInstallation()->url = str_replace("http", "https", $this->getInstallation()->url);
        $this->subModule()->getWpCli($this->getInstallation())->searchReplace("http", "https");
        $this->getInstallation()->save();
        $this->subModule()->installation($this->getInstallation())->update(['edit_url' => $this->getInstallation()->url]);
        infoLog(sprintf('Installation SSL has been enabled, Instalation #%s, Client ID #%s', $this->getInstallation()->id, $this->getInstallation()->user_id));
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
