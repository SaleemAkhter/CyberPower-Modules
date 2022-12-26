<?php


namespace ModulesGarden\WordpressManager\App\Jobs;

use ModulesGarden\WordpressManager\App\Events\InstallationCreatedEvent;
use ModulesGarden\WordpressManager\App\Helper\LangConveter;
use ModulesGarden\WordpressManager\App\Helper\UtilityHelper;
use ModulesGarden\WordpressManager\App\Modules\Directadmin;
use function ModulesGarden\WordpressManager\Core\Helper\fire;
use function ModulesGarden\WordpressManager\Core\Helper\queue;
use ModulesGarden\WordpressManager\Core\Helper\RandomStringGenerator;
use \ModulesGarden\WordpressManager\App\Models\Installation;
use function \ModulesGarden\WordpressManager\Core\Helper\infoLog;

class AutoInstallScriptJob extends AutoInstallJob
{
    public function handle($text)
    {
        $this->initParams()
             ->initServices();

        if(!$this->getModelData()['installationId']){
            $this->create();
            $this->sleep(1);
            return false;
        }else{
            $this->installationId = $this->getModelData()['installationId'];
            queue(SslEnableJob::class, ['installationId' => $this->installationId]);
            $config = $this->subModule()->getConfig($this->getInstallation());
            $config->set('MULTISITE', '0', 'constant');
            $config->set('WP_ALLOW_MULTISITE', '0', 'constant');
            //switch language
            $lang = $this->getLanguage("en");
            if($lang && $lang !="en"){
                $this->subModule()->getWpCli($this->getInstallation())->site()->switchLanguage($lang);
            }
            $this->sendWelcomeEmail();
            return true;
        }
    }

    private  function create(){
        $request=[
            'installationScript' => $this->productSetting->getAutoInstallScript(),
            "softdomain" => $this->params['domain'],
            'softproto' => $this->productSetting->getAutoInstallSoftProto() ? $this->productSetting->getAutoInstallSoftProto() : 3,
             "language" =>  $this->getLanguage("en"),
             "admin_username" => "admin",
             "admin_pass" => UtilityHelper::generatePassword(14,'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+=-'),
             "admin_email" => $this->params['model']->client->email,
             "site_desc" => "",
             "softdirectory" => "",
             "softdb" => (new RandomStringGenerator(7, true, true, true))->genRandomString(),
             "dbprefix" => "",
             "multisite" => 'off',
             "eu_auto_upgrade" => null,
             "auto_upgrade_themes" => null,
             "auto_upgrade_plugins" => null,
        ];

        if($this->params['customfields'])
        {
            $request = $this->setCustomAdminData($request);
        }

        $response = $this->subModule()->installationCreate($request);
        $model = new Installation();
        $model->domain   =  $response['__settings']['softdomain'];
        $model->url      = $response['__settings']['softurl'];
        $model->path     = $response['__settings']['softpath'];
        $model->version  = $response['__settings']['wpver'];
        $model->username = $this->subModule()->getUsername();
        $model->relation_id =  $response['insid'];
        $model->hosting_id = $this->params['serviceid'];
        $model->user_id = $this->params['userid'];
        $model->auto = 1;
        $model->save();
        $this->putModelDataAndSave(['installationId' => $model->id, "admin_username" => encrypt( $request['admin_username']), "admin_pass" => encrypt($request['admin_pass']), "adminurl" =>  $response['__settings']['adminurl']  ]);
        $this->installation = $model;
        infoLog(sprintf('Installation has been created, Instalation #%s, Client ID #%s, Hosting ID #%s', $model->id, $model->user_id, $model->hosting_id));
        fire(new InstallationCreatedEvent($model));
    }

    private function setCustomAdminData(array $request)
    {
        if(!empty($this->params['customfields']['wp_username']))
        {
            $request['admin_username'] = $this->params['customfields']['wp_username'];
        }

        if(!empty($this->params['customfields']['wp_password']))
        {
            $request['admin_pass'] = $this->params['customfields']['wp_password'];
        }

        return $request;
    }

    private function getLanguage($default){
        if ($this->params['customfields']['wpmanager_language']){
            return $this->params['customfields']['wpmanager_language'];
        }
        $langConveter = new LangConveter(false, $this->params['userid']);
        $lang = $langConveter->convert();
        if($lang){
            return $lang;
        }
        return $default;
    }

}
