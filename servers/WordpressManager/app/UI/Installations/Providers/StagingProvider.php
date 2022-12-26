<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 25, 2018)
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

use ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use ModulesGarden\WordpressManager\App\Jobs\SslEnableJob;
use ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use function ModulesGarden\WordpressManager\Core\Helper\queue;
use function ModulesGarden\WordpressManager\Core\Helper\sl;

/**
 * Description of StagingProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class StagingProvider extends BaseDataProvider implements ClientArea
{
    use BaseClientController;

    public function read()
    {
        if (!$this->getRequestValue('actionElementId') && !$this->getRequestValue('wpid'))
        {
            return;
        }
        $wpid = $this->getRequestValue('actionElementId') ? $this->getRequestValue('actionElementId') : $this->getRequestValue('wpid');
        $this->reset();
        $this->setInstallationId($wpid)
            ->setUserId($this->request->getSession('uid'));
        if ($this->getInstallation()->username)
        {
            $this->subModule()->setUsername($this->getInstallation()->username);
        }
        $this->data['installation_id'] = $wpid;
        $this->data['url']             = $this->getInstallation()->url;
        $data                          = $this->subModule()->installation($this->getInstallation())->read();
        //softdomain
        $this->data['softdomain'] = null;
        $ex                       = explode($this->getInstallation()->domain, $data['userins']['softpath']);
        $softdirectory            = (string)$data['userins']['softpath'];
        //new db name
        $randGen              = new Helper\RandomStringGenerator(7, true, true, true);
        $this->data['softdb'] = $randGen->genRandomString();
    }

    public function create()
    {

        $this->loadRequestObj();
        $this->reset()
            ->setInstallationId($this->formData['installation_id'])
            ->setUserId($this->request->getSession('uid'));
        if ($this->getInstallation()->username)
        {
            $this->subModule()->setUsername($this->getInstallation()->username);
        }
        //for plesk only
        if ($this->getInstallation()->domain_id && $this->formData['softdomain'] != $this->getInstallation()->domain)
        {
            $lang = sl("lang");
            $lang->addReplacementConstant('domain', $this->getInstallation()->domain);
            throw new \Exception($lang->abtr('You cannot create a staging with this domain, please provide the installation domain: :domain:'));
        }
        $this->subModule()->domain()->setAttributes(['domain' => $this->formData['softdomain']]);
        if ($this->formData['softdomain'] && $this->formData['softdomain'] != $this->getInstallation()->hosting->domain && !$this->subModule()->domain()->exist())
        {
            $this->domainCreate();
        }
        $post     = [
            "softproto"     => $this->formData['softproto'],
            "softdomain"    => $this->formData['softdomain'],
            "softdirectory" => $this->formData['softdirectory'],
            "softdb"        => $this->formData['softdb'],
        ];
        $response = $this->subModule()->installation($this->getInstallation())->staging($post);
        if (in_array($this->formData['softproto'], ['3', '4']))
        {
            $arguments = [
                'domain'    => $this->formData['softdomain'],
                'softpath'  => $response['__settings']['softpath'],
                'hostingId' => $this->getInstallation()->hosting_id,
            ];

            queue(SslEnableJob::class, $arguments);
        }
        Helper\infoLog(sprintf('Installation staging has been  pushed in background, Installation #%s Client ID #%s', $this->getInstallation()->id, $this->getInstallation()->user_id));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Installation staging has been pushed in background')->setCallBackFunction('reloadInstallationIndexPage');
    }

    private function domainCreate()
    {

        $this->setHostingId($this->getInstallation()->hosting_id)
            ->setUserId($this->request->getSession('uid'));
        $this->getHosting();
        foreach ($this->subModule()->getAddonDomains() as $d)
        {
            if (($d['subdomain'] && $d['subdomain'] == $this->formData['softdomain']) || ($d['domain'] && $d['domain'] == $this->formData['softdomain']))
            {
                return true;
            }
        }
        $ex        = explode(".", $this->formData['softdomain']);
        $subdomain = $ex[0];
        $request   = [
            "newDomain" => $this->formData['softdomain'],
            "subDomain" => $subdomain,
            "path"      => sprintf("/%s", $this->formData['softdomain'])
        ];
        if ($this->formData['softdirectory'])
        {
            $request['path'] .= sprintf("/%s", $this->formData['softdirectory']);
        }
        $this->subModule()->domainCreate($request);
        Helper\infoLog(sprintf('Domain has been created, Client ID #%s, Hosting ID #%s,', $this->request->getSession('uid'), $this->getInstallation()->hosting_id));
    }

    public function update()
    {

    }
}
