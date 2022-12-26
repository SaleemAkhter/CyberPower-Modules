<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Domain;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class AddonDomainsEdit extends ProviderApi
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    public function read()
    {
        if($this->getRequestValue('index') == 'editForm')
        {
            return;
        }

        parent::read();

        $this->loadLang();

        $domain = $this->userApi->domain->getStatsToEdit(new Models\Command\Domain([
            'name'  => $this->actionElementId
        ]));

        $localMail = $this->userApi->domain->getLocalMail(new Models\Command\Domain([
            'name' => $this->actionElementId
        ]));

        $details = $this->userApi->domain->viewInJson(new Models\Command\Domain([
            'name'  => $this->actionElementId
        ]));

        $phpInformation = $this->preparePhpOptions($details->php1_select);

        $this->data['phpAccess'] = strtolower($details->php);
        $this->data['php1']             = $phpInformation['selected'];
        $this->availableValues['php1']  = $phpInformation['options'];

        $this->data['domain'] = $this->actionElementId;
        $this->data['bandwidth'] = ($domain->bandwidth === "unlimited") ? "" : $domain->bandwidth;
        $this->data['diskUsage'] = ($domain->quota === "unlimited") ? "" : $domain->quota;
        $this->data['cgiAccess'] = ($domain->cgi === "ON") ? "on" : "off";
        $this->data['localMail'] = ($localMail->getLocalMail() == "yes") ? "on" : "off";
        $this->data['secureSsl'] = ($domain->ssl === "ON") ? "on" : "off";
        $this->data['redirect'] = ($domain->www_pointers === "yes") ? "on" : "off";
        $this->data['forceSsl'] = ($domain->force_ssl === "yes") ? "on" : "off";

        if($domain->bandwidth == 'unlimited')
        {
            $this->data['bandwidthUnlimited'] = 'on';
        }

        if($domain->quota == 'unlimited')
        {
            $this->data['usageUnlimited'] = 'on';
        }
    }

    protected function preparePhpOptions($phpOptions)
    {
        $options = [];

        foreach ($phpOptions as $option)
        {
            if(isset($option->selected) && $option->selected == "yes")
            {
                $options['selected'] = $option->value;
            }

            $options['options'][$option->value] = $option->text;
        }

        return $options;
    }
    private function getPHPVersionsArray()
    {
        $phpVersion = [];
        $info   = $this->userApi->systemInfo->lists();


        if(!is_null($info->getPhp()))
        {
            $phpVersion[1] = $this->lang->absoluteTranslate($info->getPhp());
        }
        if(!is_null($info->getPhp2()))
        {
            $phpVersion[2] = $this->lang->absoluteTranslate($info->getPhp2());
        }

        return $phpVersion;
    }

    /**
     *
     */
    public function update()
    {
        parent::update();

        $data = [
            'name' => $this->formData['domain'],
            'bandwidth' => $this->formData['bandwidthUnlimited'] === 'on' ? 'unlimited' : $this->formData['bandwidth'],
            'quota' => $this->formData['usageUnlimited'] === 'on' ? 'unlimited' : $this->formData['diskUsage'],
            'ssl' => strtoupper($this->formData['secureSsl']),
            'php' => strtoupper($this->formData['phpAccess']),
            'cgi' => strtoupper($this->formData['cgiAccess']),
            'redirect' =>($this->formData['redirect'] == "on") ? "yes" : "no",
            'forceSsl' => ($this->formData['forceSsl']  == "on") ? "yes" : "no",
            'localMail' => $this->formData['localMail']
        ];

        $this->userApi->domain->update(new Models\Command\Domain($data));

        if(isset($this->formData['php1']))
        {
            $tempData = [
                'php1Select' => $this->formData['php1']
            ];
            $data = array_merge($tempData, $data);

            $this->userApi->domain->updatePhpVersion(new Models\Command\Domain($data));
        }

        if($this->getProductConfiguration('package') != "custom" || $this->getProductConfiguration('dnscontrol') == "on")
        {
            $data['localMail'] = $this->formData['localMail'] == 'on' ? $this->formData['localMail'] : 'off';
            $this->userApi->domain->setLocalMail(new Models\Command\Domain($data));
        }

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('addonDomainHasBeenUpdated');
    }

    public function reload()
    {
        foreach($this->formData as $key => $value)
        {
            $this->data[$key] = $value;
        }
    }

}
