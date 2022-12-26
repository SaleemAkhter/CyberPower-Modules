<?php


namespace ModulesGarden\WordpressManager\App\Jobs;


use ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use ModulesGarden\WordpressManager\App\Models\ProductSetting;
use ModulesGarden\WordpressManager\App\Services\EmailService;
use ModulesGarden\WordpressManager\Core\ModuleConstants;
use ModulesGarden\WordpressManager\Core\Queue\Job;

class AutoInstallJob  extends BaseJob
{

    /**
     * @var EmailService
     */
    protected $emailService;
    /**
     * @var ProductSetting
     */
    protected $productSetting;
    protected $params;

    public function initParams()
    {
         if (!$this->getModelData()['hostingId']) {
            new \InvalidArgumentException("Job model: argument hostingId cannot be empty");
        }
        if (!function_exists('ModuleBuildParams'))
        {
            require_once ModuleConstants::getFullPathWhmcs('includes') . DIRECTORY_SEPARATOR . "modulefunctions.php";
        }
        $this->params = \ModuleBuildParams($this->getModelData()['hostingId']);
        $this->setHostingId($this->params['serviceid']);
        $this->setUserId($this->params['userid']);
        $this->installationId = $this->getModelData()['installationId'];
        return $this;
    }

    protected function initServices(){
        $this->emailService = new EmailService();
        $this->productSetting = ProductSetting::ofProductId($this->params['packageid'])->firstOrFail();
        return $this;
    }

    protected function sendWelcomeEmail(){
        if(!$this->productSetting->getAutoInstallEmailTemplate()){
            return;
        }
        $emailVars['customvars']['installation'] = $this->getInstallation()->toArray();
        $emailVars['customvars']['admin_username'] = decrypt($this->getModelData()['admin_username']);
        $emailVars['customvars']['admin_pass'] = decrypt( $this->getModelData()['admin_pass']);
        $emailVars['customvars']['adminurl'] =  $this->getModelData()['adminurl'];
        //init email template
        $this->emailService->template($this->productSetting->getAutoInstallEmailTemplate());
        $emailVars['id'] = $this->params['serviceid'];
        //send
        return $this->emailService->vars($emailVars)->send();
    }

}