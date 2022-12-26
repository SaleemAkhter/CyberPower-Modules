<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\HotlinkProtection\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class DeleteUrlProvider extends HotlinkProtectionProvider
{

    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    public function read()
    {
        $this->data['id']   = $this->actionElementId;
    }

    public function delete()
    {
        $domain = $this->getRequestValue('domain');
        $this->loadUserApi();

        $result = $this->userApi->hotlinkProtection->lists($domain);
        $id = $this->formData['id'];

        $data = [
            'action'      => 'multiple',
            'delete'      => 'yes',
            'domain'      => $domain,
            'json'        => 'yes',
            'urlToDelete' => $result->urls[$id],
        ];

        $this->userApi->hotlinkProtection->delete($data);

        return (new ResponseTemplates\HtmlDataJsonResponse())
        ->addRefreshTargetId('hotlinkProtection')
        ->setMessageAndTranslate('confirmUrlDelete');
        
    }
}
