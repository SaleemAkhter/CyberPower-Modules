<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\CatchEmails\Providers;


use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\ErrorHandler\Exceptions\ApiException;
use ModulesGarden\Servers\DirectAdminExtended\Core\HandlerError\Exceptions\Exception;
use ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\CatchEmails as Model;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates\DataJsonResponse;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates\RawDataJsonResponse;

class CatchEmails extends ProviderApi implements ClientArea
{
    use Lang;
    public function read()
    {
        parent::read();

        $this->data['domain'] = $this->actionElementId;
        $this->data['privileged'] = true;

        try {
            $result = $this->userApi->catchEmails->getCatchEmail(new Model($this->data));
        } catch(ApiException $exc)
        {
            if(empty($exc->getMessage()))
            {
               $this->data['privileged'] = false;
            }
        }
        $this->loadLang();

        $this->availableValues['option'] =
        [
        'fail'    => $this->lang->absoluteTranslate('fail'),
        'ignore'    => $this->lang->absoluteTranslate('ignore'),
        'address'    => $this->lang->absoluteTranslate('address')
        ];

        if($result->value == ':blackhole:')
        {
            $this->data['option'] = 'ignore';
        }
        elseif ($result->value == ':fail:')
        {
            $this->data['option'] = 'fail';
        }
        else
        {
            $this->data['email'] = $result->value;
            $this->data['option'] = 'address';
        }
    }

    public function update()
    {
        parent::update();
        $data = [];

        if($this->formData['option'] == 'fail')
        {
            $data['option'] = ':fail:';
        }
        elseif($this->formData['option'] == 'ignore')
        {
            $data['option'] = ':blackhole:';
        }
        else
        {
            $data['option'] = 'address';
        }

        $data = array_merge($this->formData, $data);

        $this->userApi->catchEmails->edit(new Model($data));
        return (new RawDataJsonResponse())->setMessageAndTranslate('catchEmailsChanged');
    }

    public function create()
    {
    }

    public function delete()
    {
    }

}