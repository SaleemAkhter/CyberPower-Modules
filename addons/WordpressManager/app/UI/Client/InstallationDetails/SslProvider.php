<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 18, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails;

use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;

/**
 * Description of SslProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class SslProvider extends BaseDataProvider implements ClientArea
{

    use BaseClientController;

    public function read()
    {
        $this->loadRequestObj();
        $this->reset();
        
        $this->setUserId($this->request->getSession('uid'))
                ->setInstallationId($this->request->get('wpid'));
        $this->data['ssl'] = $this->getInstallation()->isHttps() ? "on" : "off";
        $this->data['wpid'] = $this->request->get('wpid');
    }

    private function on() 
    {
        if(!$this->getInstallationId())
        {
            $this->setInstallationId($this->formData['wpid']);
        }

        if($this->getInstallation()->username){
            $this->subModule()->setUsername($this->getInstallation()->username);
        }
        $this->subModule()->ssl()->setInstallation($this->getInstallation())->on();
        //Replace http to https
        if (!preg_match('/https/', $this->getInstallation()->url))
        {
            $this->getInstallation()->url = str_replace("http", "https", $this->getInstallation()->url);
            $this->subModule()->getWpCli($this->getInstallation())->searchReplace("http", "https");
            $this->getInstallation()->save();
        }
        $this->subModule()->installation($this->getInstallation())->update(['edit_url' => $this->getInstallation()->url]);
        Helper\infoLog(sprintf('Installation SSL has been enabled, Instalation #%s, Client ID #%s', $this->getInstallation()->id, $this->getInstallation()->user_id));
        sl('lang')->addReplacementConstant('domain', $this->getInstallation()->domain);
        return (new ResponseTemplates\HtmlDataJsonResponse())
                        ->setMessageAndTranslate('The domain :domain: SSL has been enabled successfully')
                        ->setCallBackFunction('wpSslChange');
    }

    private function off()
    {
        if(!$this->getInstallationId())
        {
            $this->setInstallationId($this->formData['wpid']);
        }

        if($this->getInstallation()->username){
            $this->subModule()->setUsername($this->getInstallation()->username);
        }
        $this->subModule()->ssl()->setInstallation($this->getInstallation())->off();
        //Replace https to http
        if (preg_match('/https/', $this->getInstallation()->url))
        {
            $this->getInstallation()->url = str_replace("https", "http", $this->getInstallation()->url);
            $this->subModule()->getWpCli($this->getInstallation())->searchReplace("https", "http");
            $this->getInstallation()->save();
        }
        $this->subModule()->installation($this->getInstallation())->update(['edit_url' => $this->getInstallation()->url]);
        sl('lang')->addReplacementConstant('domain', $this->getInstallation()->domain);
        return (new ResponseTemplates\HtmlDataJsonResponse())
                        ->setMessageAndTranslate('The domain :domain: SSL has been disabled successfully')
                        ->setCallBackFunction('wpSslChange');
    }

    public function ssl()
    {
        $this->loadRequestObj();
        $this->reset();

        $wpid = $this->request->get('wpid') ?? $this->formData['wpid'];

        $this->setUserId($this->request->getSession('uid'))
                ->setInstallationId($wpid);
        if ($this->formData['backup'] == "on")
        {
            $data = [
                'installationId'  => $this->getInstallation()->relation_id,
                'backupDirectory' => 0,
                'backupDataDir'   => 0,
                'backupDatabase'  => 1,
            ];
            Helper\infoLog(sprintf("Backup creating in progress, Installation ID #%s, Client ID #%s", $this->getInstallation()->id, $this->getInstallation()->user_id));
            $this->subModule()->backupCreate($data);
        }
        if ($this->formData['ssl'] == "on")
        {
            return $this->on();
        }
        return $this->off();
    }

    public function update()
    {
        
    }
}
