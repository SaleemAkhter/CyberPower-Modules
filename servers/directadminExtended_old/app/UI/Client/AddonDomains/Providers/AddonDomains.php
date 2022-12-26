<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class AddonDomains extends ProviderApi
{

    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    public function read()
    {
        $this->loadLang();
        $this->data['bandwidth']                = $this->formData['bandwidth'];
        $this->availableValues['bandwidth']     = [
            'unlimited' => $this->lang->absoluteTranslate('unlimited'),
            'custom'    => $this->lang->absoluteTranslate('custom')
        ];

        $this->data['diskspace']            = $this->formData['diskspace'];
        $this->availableValues['diskspace'] = [
            'unlimited' => $this->lang->absoluteTranslate('unlimited'),
            'custom'    => $this->lang->absoluteTranslate('custom')
        ];

        if($this->formData['domain'])
        {
            $this->data['domain']           = $this->formData['domain'];
            $this->data['bandwidthCustom']  = $this->formData['bandwidthCustom'];
            $this->data['diskspaceCustom']  = $this->formData['diskspaceCustom'];
            $this->data['ssl']              = $this->formData['ssl'];
            $this->data['cgi']              = $this->formData['cgi'];
            $this->data['php']              = $this->formData['php'];
            $this->data['localMail']        = $this->formData['localMail'];
            $this->data['forceSsl']         = $this->formData['forceSsl'];
        }
        else
        {
            $this->data['ssl']              = 'off';
            $this->data['php']              = 'on';
            $this->data['localMail']        = 'on';
            $this->data['cgi']              = 'on';
        }
    }

    public function create()
    {
        parent::create();

        $data = [
            'name'      => $this->formData['domain'],
            'bandwidth' => $this->formData['bandwidth'] === 'off' ? $this->formData['bandwidthCustom'] : $this->formData['bandwidth'],
            'ssl'       => $this->formData['ssl'] == 'on' ? strtoupper($this->formData['ssl']) : '',
            'cgi'       => $this->formData['cgi'] == 'on' ? strtoupper($this->formData['cgi']) : '',
            'php'       => $this->formData['php'] == 'on' ? strtoupper($this->formData['php']) : '',
            'forceSsl' => ($this->formData['forceSsl']  == "on") ? "yes" : "no",
        ];
        if($this->formData['diskspace'] === 'off')
        {
            $data['quota'] = $this->formData['diskspaceCustom'];
        }
        else
        {
            $data['uquota'] = $this->formData['quota'];
        }
        $this->userApi->domain->create(new Models\Command\Domain($data));

        if($this->getProductConfiguration('package') != "custom" || $this->getProductConfiguration('dnscontrol') == "on")
        {
            $data['localMail'] = $this->formData['localMail'] == 'on' ? $this->formData['localMail'] : 'off';
            $this->userApi->domain->setLocalMail(new Models\Command\Domain($data));
        }


        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('addonDomainHasBeenCreated');
    }

    public function delete()
    {
        parent::delete();

        $data = [
            'name' => $this->formData['domain']
        ];
        $this->userApi->domain->delete(new Models\Command\Domain($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('addonDomainHasBeenDeleted');
    }
    
    public function deleteMany()
    {
        parent::delete();

        $data        = [];
        $domainsName = $this->getRequestValue('massActions', []);
        $this->loadLang();
        foreach ($domainsName as $name)
        {
            if($name === $this->getWhmcsParamByKey('domain'))
            {
                return (new ResponseTemplates\RawDataJsonResponse())
                    ->setStatusError()
                    ->setMessage($this->lang->addReplacementConstant('domain',$name)->absoluteTranslate('domainCannotBeDeleted'));
            }
            $data[] = new Models\Command\Domain(['name' => $name]);
        }
        $this->userApi->domain->deleteMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('addonDomainsHaveBeenDeleted');
    }

    public function update()
    {

    }

    public function reload()
    {
        $this->data['domain']                   = (is_null($this->formData['domain'])) ? $this->data['domain'] : $this->formData['domain'] ;
        $this->data['bandwidth']                = (is_null($this->formData['bandwidth'])) ? $this->data['bandwidth'] : $this->formData['bandwidth'];
        $this->data['bandwidthCustom']          = (is_null($this->formData['bandwidthCustom'])) ? $this->data['bandwidthCustom'] : $this->formData['bandwidthCustom'];
        $this->data['diskspace']                = (is_null($this->formData['diskspace'])) ? $this->data['diskspace'] : $this->formData['diskspace'];
        $this->data['diskspaceCustom']          = (is_null($this->formData['diskspaceCustom'])) ? $this->data['diskspaceCustom'] : $this->formData['diskspaceCustom'];
        $this->data['ssl']                      = (is_null($this->formData['ssl'])) ? $this->data['ssl'] : $this->formData['ssl'];
        $this->data['cgi']                      = (is_null($this->formData['cgi'])) ? $this->data['cgi'] : $this->formData['cgi'];
        $this->data['php']                      = (is_null($this->formData['php'])) ? $this->data['php'] : $this->formData['php'];
        $this->data['localMail']                = (is_null($this->formData['localMail'])) ? $this->data['localMail'] : $this->formData['localMail'];
    }

    public function suspendUnsuspend()
    {
        parent::suspendUnsuspend();

        $data = [
            'name' => $this->formData['domain']
        ];

        $this->userApi->domain->suspendUnsuspend(new Models\Command\Domain($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('singleToggleSuspend');
    }

    public function suspendUnsuspendMany()
    {
        parent::suspendUnsuspend();

        $data        = [];
        $domainsName = $this->getRequestValue('massActions', []);
        foreach ($domainsName as $name) {
            $data[] = new Models\Command\Domain([
                'name' => $name
            ]);
        }

        $this->userApi->domain->suspendUnsuspendMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('multipleToggleSuspend');
    }
}
