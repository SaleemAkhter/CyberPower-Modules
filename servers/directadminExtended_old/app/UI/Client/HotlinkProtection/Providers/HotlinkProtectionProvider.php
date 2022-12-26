<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\HotlinkProtection\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class HotlinkProtectionProvider extends ProviderApi
{

    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    public function read()
    {
        $domain = $this->getRequestValue('domain');

        $this->loadUserApi();
        $result = $this->userApi->hotlinkProtection->lists($domain);

        $this->data['allowBlankReferer'] = $result->blankReferer == 'checked' ? 'on' : '';
        $this->data['domain']            = $domain;
        $this->data['redirect']          = $result->redirectTo == 'checked' ? 'on' : '';
        $this->data['redirectUrl']       = $result->redirectUrl;
        $this->data['fileTypes']         = $result->protectedFiles;
        $this->data['enable']            = $result->isEnabled == 'checked' ? 'on' : '';
    }

    public function create()
    {
        parent::create();

        $data = [
            'action'             => 'save',
            'isEnabled'          => $this->formData['enable'] == 'on' ? 'yes' : 'no',
            'urls'               => $this->formData['url'],
            'redirectTo'         => $this->formData['redirect'] == 'on' ? 'url' : 'forbidden',
            'redirectUrl'        => $this->formData['redirectUrl'],
            'json'               => 'yes',
            'protectedFiles'     => $this->formData['fileTypes'],
            'blankReferer'       => $this->formData['allowBlankReferer'] == 'on' ? 'yes' : 'no',
            'domain'             => $this->formData['domain'],
        ];

        $this->userApi->hotlinkProtection->save(new Models\Command\HotlinkProtection($data));

        if($this->formData['url'] == null)
        {
            $message = 'confirmSettings'; 
        } else {
            $message = 'confirmUrlCreate';
        }

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->addRefreshTargetId('hotlinkProtection')
            ->setMessageAndTranslate($message);
    }
}
