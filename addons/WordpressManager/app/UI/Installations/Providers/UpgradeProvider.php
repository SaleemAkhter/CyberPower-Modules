<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Dec 20, 2018)
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

use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use ModulesGarden\WordpressManager\App\Repositories\InstallationRepository;
use ModulesGarden\WordpressManager\App\Interfaces\WordPressModuleInterface;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use \ModulesGarden\WordpressManager\App\Interfaces\ChangeDomainInterface;
use \ModulesGarden\WordpressManager\App\Models\PluginPackage;
use \ModulesGarden\WordpressManager\App\Repositories\HostingRepository;
use \ModulesGarden\WordpressManager\Core\Models\Whmcs\Client;
/**
 * Description of UpgradeProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class UpgradeProvider extends BaseDataProvider implements ClientArea
{
    use BaseClientController;
    
    
    public function upgradeRead(){
        
        $this->loadRequestObj();
        $this->reset();
        $id = $this->formData['installation_id']? $this->formData['installation_id'] : $this->request->get('wpid');
        
        $this->setInstallationId($id)
              ->setUserId($this->request->getSession('uid'));
        $this->data['installation_id'] = $id;
        if($this->getInstallation()->username){
            $this->subModule()->setUsername($this->getInstallation()->username);
        }
        $details =  $this->subModule()->installation($this->getInstallation())->upgrade([]);
        $this->data['version'] = $details['software']['ver'];
    }
    
    public function read()
    {

    }

    public function create()
    {
        $this->loadRequestObj();
        $this->reset();
        $id = $this->formData['installation_id']? $this->formData['installation_id'] : $this->request->get('wpid');
        $this->setInstallationId($id)
              ->setUserId($this->request->getSession('uid'));
        if($this->getInstallation()->username){
            $this->subModule()->setUsername($this->getInstallation()->username);
        }
        //Backup
        if ($this->formData['backup'] == 'on')
        {
            $data = [
                'installationId'  => $this->getInstallation()->relation_id,
                'backupDirectory' => 1,
                'backupDataDir'   => 1,
                'backupDatabase'  => 1,
                'note'            => 'Backup before installation update'
            ];
            $this->subModule()->backupCreate($data);
        }
        $this->subModule()->installation($this->getInstallation())->upgrade(['softsubmit' => 1]);
        if($this->formData['version']){
            $this->getInstallation()->version = $this->formData['version'];
            $this->getInstallation()->save();
        }
        Helper\infoLog(sprintf('Installation has been upgraded successfully #%s, Client ID #%s', $this->getInstallation()->id, $this->getInstallation()->user_id));
        return (new ResponseTemplates\HtmlDataJsonResponse())
                ->setMessageAndTranslate('Installation has been upgraded successfully')
                ->setCallBackFunction('wpSslChange');
    }

    public function update()
    {
        
    }
}
