<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ApacheHandlers\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class ApacheHandlers extends ProviderApi
{

    public function read()
    {
        parent::loadUserApi();

        $this->data['domain']              = [];
        $this->availableValues['domain']   = $this->getDomainList();


        $this->data['name'] = $this->actionElementId;
    }

    public function create()
    {
        parent::create();

        $explodeExt = array_filter(explode(' ', $this->formData['extension']));

        if(empty($explodeExt)){
            return (new ResponseTemplates\HtmlDataJsonResponse())->setStatusError()->setMessageAndTranslate('extensionCanNotBeEmpty');
        }

        foreach ($explodeExt as $ext)
        {
            $data = [
                'name'      => $this->formData['handler'],
                'extension' => trim($ext),
                'domain'    => $this->formData['domain']
            ];
            $this->userApi->apacheHandler->add(new Models\Command\ApacheHandler($data));
        }

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('handlerHasBeenCreated');
    }

    public function delete()
    {
        parent::delete();

        $data = [
            'name'   => $this->formData['name'],
            'domain' => $this->formData['domain']
        ];
        $this->userApi->apacheHandler->delete(new Models\Command\ApacheHandler($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('handlerHasBeenDeleted');
    }

    public function update()
    {
        parent::update();

        $allExtensions  = array_filter(explode(' ', $this->formData['extHidden']));
        $leftExtensions = array_filter(explode(' ', $this->formData['extensions']));
        $deleteDiff     = array_diff($allExtensions, $leftExtensions);
        $addDiff        = array_diff($leftExtensions, $allExtensions);

        if(empty($leftExtensions)){
            return (new ResponseTemplates\HtmlDataJsonResponse())->setStatusError()->setMessageAndTranslate('extensionCanNotBeEmpty');
        }

        if (!$leftExtensions)
        {
            $this->formData['name'] = $this->formData['hanHidden'];
            return $this->delete();
        }
        foreach ($deleteDiff as $extension)
        {
            $data = [
                'name'      => $this->formData['hanHidden'],
                'domain'    => $this->formData['domainHidden'],
                'extension' => $extension
            ];
            $this->userApi->apacheHandler->deleteExtension(new Models\Command\ApacheHandler($data));
        }
        foreach ($addDiff as $extension)
        {
            $this->formData['extension'] = $extension;
            $this->formData['handler']   = $this->formData['hanHidden'];
            $this->formData['domain']    = $this->formData['domainHidden'];
            $this->create();
        }

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('handlerHasBeenUpdated');
    }

    public function deleteMany()
    {
        parent::delete();

        $data        = [];

        $domainsName = $this->getRequestValue('massActions', []);

        foreach ($domainsName as $name) {

            $result[] = json_decode(base64_decode($name), true);
        }

        foreach ($result as $key => $elem)
        {
            $data[$elem['domain']][] = $elem['name'];
        }

        $this->userApi->apacheHandler->deleteMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('multipleHandlersHaveBeenDeleted');
    }

}
