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

use ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use function ModulesGarden\WordpressManager\Core\Helper\sl;

/**
 * Description of InstanceImageProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class InstanceImageProvider extends BaseModelDataProvider implements ClientArea
{
    use BaseClientController;

    public function __construct()
    {
        parent::__construct(main\App\Models\InstanceImage::class);
    }

    public function read()
    {
        $this->data = (array)$this->formData;
        $dbData     = $this->model->OfUserId($this->request->getSession('uid'))->ofInstallationId($this->request->get('wpid'))->first();
        if ($dbData !== null)
        {
            $this->data = array_merge($this->data, $dbData->toArray());
        }

    }

    public function create()
    {
        $wpId = $this->request->get('wpid') ?: $this->request->get('formData')['wpid'];

        $this->loadRequestObj();
        $this->reset();
        $this->setUserId($this->request->getSession('uid'))
            ->setInstallationId($wpId);
        if ($this->formData['markAsImage'] == "off")
        {
            return $this->delete();
        }
        $this->formData['installation_id'] = $this->getInstallation()->id;
        $this->formData['ftp_pass']        = encrypt($this->formData['ftp_pass']);
        $this->formData['soft']            = $this->getInstallation()->getSoftId();
        $this->formData['domain']          = $this->getInstallation()->domain;
        $this->formData['installed_path']  = $this->getInstallation()->path;
        $this->formData['enable']          = 1;
        $this->formData['user_id']         = $this->getUserId();
        $this->model->fill($this->formData)->save();
        sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setMessageAndTranslate('Instance Image has been turned on successfully')
            ->setCallBackFunction('wpSslChange');
    }

    public function update()
    {
        $wpId = $this->request->get('wpid') ?: $this->request->get('formData')['wpid'];

        $this->loadRequestObj();
        $this->reset();
        $this->setUserId($this->request->getSession('uid'))
            ->setInstallationId($wpId);
        if ($this->formData['markAsImage'] == "off")
        {
            return $this->delete();
        }
        $this->formData['installation_id'] = $this->getInstallation()->id;
        $this->formData['ftp_pass']        = encrypt($this->formData['ftp_pass']);
        $this->formData['soft']            = $this->getInstallation()->getSoftId();
        $this->formData['domain']          = $this->getInstallation()->domain;
        $this->formData['installed_path']  = $this->getInstallation()->path;
        $this->formData['enable']          = 1;
        $this->formData['user_id']         = $this->getUserId();
        $dbData                            = $this->model->OfUserId($this->request->getSession('uid'))->ofInstallationId($wpId)->first();
        if ($dbData === null)
        {
            return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('ItemNotFound')->setStatusError()->setCallBackFunction($this->callBackFunction);;
        }
        $dbData->fill($this->formData)->save();
        sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setMessageAndTranslate('Instance image :name: has updated successfully')
            ->setCallBackFunction('wpSslChange');
    }

    public function delete()
    {
        $wpId = $this->request->get('wpid') ?: $this->request->get('formData')['wpid'];

        sl('lang')->addReplacementConstant('name', $this->formData['name']);
        $this->model->OfUserId($this->request->getSession('uid'))->ofInstallationId($wpId)->delete();
        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setMessageAndTranslate('Instance Image has been turned off successfully')
            ->setCallBackFunction('wpSslChange');
    }
}
