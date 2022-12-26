<?php
/* * ********************************************************************
*  VultrVps Product developed. (27.03.19)
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

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\ChangeOs\Providers;

use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\App\Enum\CustomField;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;


class ChangeOsProvider extends BaseDataProvider implements ClientArea
{

    public function read()
    {
        if (!$this->actionElementId )
        {
            return;
        }
        $this->data = \json_decode(base64_decode($this->actionElementId),true);
    }

    public function create()
    {
    }

    public function update()
    {
        $response = (new InstanceFactory())->fromWhmcsParams()->changeOs($this->formData['id']);
        if($response->instance->default_password){
            $hosting              = $this->getWhmcsParamByKey('model');
            $hosting->password = encrypt((string)$response->instance->default_password);
            $hosting->save();
        }
        return (new HtmlDataJsonResponse())
            ->setStatusSuccess()
            ->setMessageAndTranslate('The OS has been updated successfully');
    }


}