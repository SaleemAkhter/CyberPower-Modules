<?php


namespace ModulesGarden\WordpressManager\App\Jobs;

use ModulesGarden\WordpressManager\App\Models\InstanceImage;
use function ModulesGarden\WordpressManager\Core\Helper\queue;
use \ModulesGarden\WordpressManager\App\Models\Installation;
use function \ModulesGarden\WordpressManager\Core\Helper\infoLog;
use ModulesGarden\WordpressManager\Core\Helper\RandomStringGenerator;

class AutoInstallInstanceImageJob  extends AutoInstallJob
{
    public function handle($text)
    {
        $this->initParams()
             ->initServices();

        if(!$this->getModelData()['softurl']){
            $this->import();
            $this->sleep(1);
            return false;
        }else if($this->find() instanceof Installation ){
            //Empty version
            if(!$this->installation->version && $this->getModelData()['ver']){
                $this->installation->version = $this->getModelData()['ver'];
                $this->installation->save();
            }
            if(!$this->installation->auto){
                $this->installation->auto = 1;
                $this->installation->save();
            }
            queue(SslEnableJob::class, ['installationId' => $this->installation->id]);
            $config = $this->subModule()->getConfig($this->getInstallation());
            $config->set('MULTISITE', '0', 'constant');
            $config->set('WP_ALLOW_MULTISITE', '0', 'constant');
            $this->sendWelcomeEmail();
            return true;
        }else{
            $this->sleep(1);
            return false;
        }

    }

    private function import(){
        /* @var $instanceImage InstanceImage */
        $instanceImage = InstanceImage::findOrFail($this->productSetting->getAutoInstallInstanceImage());
        //make request import instace image
        $request=[
            "softdomain" => $this->params['domain'],
            'softproto' => $this->productSetting->getAutoInstallSoftProto() ? $this->productSetting->getAutoInstallSoftProto() : 3,
            "dest_directory" => "",
            "softdb" => (new RandomStringGenerator(7, true, true, true))->genRandomString(),
            'protocol'  => $instanceImage->protocol,
            'server_host'  => $instanceImage->server_host,
            'port'  => $instanceImage->port,
            'ftp_user'  => $instanceImage->ftp_user,
            'ftp_path' => $instanceImage->ftp_path,
            'soft'  => $instanceImage->soft,
            'domain'  => $instanceImage->domain,
            'ftp_pass'  => decrypt($instanceImage->ftp_pass),
        ];
        
        if($instanceImage->installed_path)
        {
            $request['Installed_path'] = $instanceImage->installed_path;
        }
        
        $response = $this->subModule()->import($request);
        $url      = $response['__settings']['softurl'];
        $softpath = $response['__settings']['softpath'];
        $this->putModelDataAndSave(['softurl' => $url ,"softpath" => $softpath, "ver" => $response['software']['ver'] ]);
        infoLog(sprintf('Installation import creating in progress, URL %s, Client ID #%s, Hosting ID #%s', $url, $this->params['userid'], $this->params['serviceid']));
    }


    private function find(){
        return $this->installation = Installation::ofHostingId($this->params['serviceid'])->ofUrl($this->getModelData()['softurl'])->first();
    }
}
