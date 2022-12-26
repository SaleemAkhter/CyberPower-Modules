<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 24, 2018)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\UI\Installations\Providers;

use ModulesGarden\WordpressManager\App\Interfaces\WordPressModuleInterface;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use ModulesGarden\WordpressManager\App\Models\WebsiteDetails;
use \ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
use  function \ModulesGarden\WordpressManager\Core\Helper\queue;
use \ModulesGarden\WordpressManager\Core\Helper;
use \ModulesGarden\WordpressManager\App\Jobs\SslEnableJob;
use ModulesGarden\WordpressManager\App\Repositories\InstallationRepository;
use ModulesGarden\WordpressManager\App\Events\InstallationCreatedEvent;

/**
 * Description of ImportProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class ImportProvider extends BaseDataProvider implements ClientArea
{
    use BaseClientController;
    
    public function read()
    {
        $this->data = $this->formData;
        //softdb
        $randGen = new Helper\RandomStringGenerator(7, true, true, true);
        $this->data['softdb'] = $randGen->genRandomString();    
    }
    
    public function create()
    {
        $this->reset()
                ->setHostingId($this->formData['hostingId'])
                ->setUserId($this->request->getSession('uid'));
        $post = $this->formData;
        $post['soft']=26;
        $post['auth_password']=1;
        unset($post['hostingId']);
        $username=Helper\loggedinUsername();
        if($username){
            $this->subModule()->setUsername($username);
        }
        // $this->subModule()->domain()->setAttributes(['domain' => $this->formData['softdomain'], 'rootdomain' => $this->getHosting()->domain ]);
        // if ($this->formData['softdomain'] && $this->formData['softdomain'] != $this->getHosting()->domain
        //         && !$this->subModule()->domain()->exist())
        // {
        //     $ex        = explode(".", $this->formData['softdomain']);
        //     $subdomain = $ex[0];
        //     $this->subModule()->domain()->setAttributes([
        //         'domain'    => $this->formData['softdomain'],
        //         'dir'       => sprintf("/%s", $this->formData['softdomain']),
        //         'subdomain' => $subdomain,
        //         'rootdomain' => $this->getHosting()->domain
        //     ])->create();
        //     Helper\infoLog(sprintf('Domain %s has been created, Client ID #%s, Hosting ID #%s', $this->formData['softdomain'],  $this->request->getSession('uid'),$this->getHostingId()));
        // }
        $response = $this->subModule()->import($post);
        // logActivity(json_encode($response));
        $url      = $response['__settings']['softurl'];
        
        if(in_array($this->formData['softproto'], ['3','4'])){
            $arguments = [
                      'domain' => $this->formData['softdomain'],
                      'softurl' => $response['__settings']['softurl'],
                      'softpath'  => $response['__settings']['softpath'],
                      'hostingId' =>  $this->getHostingId(),
                ];
            queue(SslEnableJob::class, $arguments);
        }
        Helper\infoLog(sprintf('Installation import creating in progress, URL %s, Client ID #%s, Hosting ID #%s', $url, $this->request->getSession('uid'),$this->getHostingId()));
        sl('lang')->addReplacementConstant('url', $url);
        
        return (new HtmlDataJsonResponse())
                        ->setMessageAndTranslate('Installation import creating in progress. URL to installation: :url:')->setCallBackFunction("installationInProgress")->setData(['hostingId'=>$this->formData['hostingId'],'checkid'=>$response['done'],'url'=>$url,'loginurl'=>$loginurl]);
    }

    public function update()
    {
        
    }
    
    public function checkProgress()
    {
        $this->formData=$_POST;
        $this->reset()
                ->setHostingId($this->formData['hostingId'])
                ->setUserId($this->request->getSession('uid'));
        $post = ['ref'=>$this->formData['ref']];
        $username=Helper\loggedinUsername();
        if($username){
            $this->subModule()->setUsername($username);
        }
        $progress= $this->subModule()->importProgress($post);

        if($progress['progress']==100){
            $installation=$this->synchronize($this->subModule(),$this->formData['hostingId'],$progress);
            if($installation){
                $progress['loginurl']=BuildUrl::getUrl('home', 'controlPanel',['id'=>$_GET['id'],'wpid'=>$installation->id]);
            }
        }
        return $progress;
    }
    private function getNewInstallationId($sign_on_url)
    {
        $url=parse_url($sign_on_url);
        $result=[];
        parse_str($url['query'], $result);
        if(isset($result['insid'])){
            return $result['insid'];
        }
        return false;
    }
    private function synchronize(WordPressModuleInterface $module, $hosting,$progress)
    {
        $hosting = Hosting::findOrFail($hosting);

        $insid=$this->getNewInstallationId($progress['info']->sign_on_url);
        $installation=$module->installationDetail($insid);
        if (!$installation['insid']){
            return ;
        }
        $repository = new InstallationRepository;
        $model      = $repository->forHostingAndRelation($hosting, $installation['insid']);
        //for plesk
        if (!$model->id && Installation::ofRelationId($model->relation_id)->ofUserId($model->user_id)->count())
        {
            return ;
        }
        if ($model->id)
        {
            return $model;
        }
        $model->domain   = $installation['domain'];
        $model->url      = $installation['url'];
        $model->path     = $installation['path'];
        $model->version  = $installation['version'];
        $model->additional_data=$installation;
        $model->username = $module->getUsername();
        if ($installation['domain_id'])
        {
            $model->domain_id = $installation['domain_id'];
        }
        $model->save();
        $this->createWebsiteDetails();
        Helper\fire(new InstallationCreatedEvent($model));
        return $model;
    }
    protected function createWebsiteDetails()
    {
        $inst = Installation::pluck('id')->max();
        if ($inst == null)
        {
            $inst = 1;
        }
        (new WebsiteDetails(['wpid' => $inst]))->save();
    }

}
