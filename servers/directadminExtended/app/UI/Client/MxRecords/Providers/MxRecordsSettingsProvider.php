<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MxRecords\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\DnsManage;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\DnsManage as Model;

class MxRecordsSettingsProvider extends ProviderApi implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;


    public function read()
    {
        $this->loadLang();
        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount"){
            $this->loadResellerApi([],false);
            $domains=$this->resellerApi->domain->lists();
            $domainlist=$domains->getResponse();
            if(!empty($domainlist)){
                $domain=$domainlist[0];
                $domainname=$domain->name;
                $data     = [
                    'domain' => $domainname
                ];
                $settings   = $this->resellerApi->dnsManage->mxsettings(new Model($data));
            }else{
                $settings=[];
            }

        }elseif($this->getWhmcsParamByKey('producttype')  == "server"){
            $this->loadAdminApi();
            $domains=$this->adminApi->domain->lists();
            $domainlist=$domains->getResponse();
            if(!empty($domainlist)){
                $domain=$domainlist[0];
                $domainname=$domain->name;
                $data     = [
                    'domain' => $domainname
                ];
                $settings   = $this->adminApi->dnsManage->mxsettings(new Model($data));
            }else{
                $settings=[];
            }
        }else{
            $domain = $this->getRequestValue('domain');

            $data = [
                'domain' => $domain
            ];
            // debug($domain);die(';');
            $this->loadUserApi();
            $domains=$this->userApi->domain->lists();
            $domainlist=$domains->getResponse();
            if(!empty($domainlist)){
                $domain=$domainlist[0];
                $domainname=$domain->name;
                $data     = [
                    'domain' => $domainname
                ];
                $settings   = $this->userApi->dnsManage->mxsettings(new Model($data));
            }else{
                $settings=[];
            }

        }

        $this->data['usethisserver']=($settings->internal=="yes")?"on":"off";
        $this->data['affectpointers']=($settings->DNS_AFFECT_POINTERS_DEFAULT=="yes")?"on":"off";
        $templates=[];
        foreach ($settings->mx_templates->mx_templates_select as $key => $template) {
            $templates[$template->value]=$this->lang->absoluteTranslate($template->text);
            if(isset($template->selected)){
                $this->data['mxtemplate']=$template->value;
            }
        }
        $this->availableValues['mxtemplate'] = $templates;

    }
    public function create()
    {
        parent::create();
        $data=[
            'mx_template'=>$this->formData['mxtemplate'],
            'affect_pointers'=>($this->formData['affectpointers']=="on")?'yes':'',
            'internal'=>($this->formData['usethisserver']=="on")?'yes':''
        ];
        $tmp = [];
         if($this->getWhmcsParamByKey('producttype')  == "reselleraccount"){
            $this->loadResellerApi([],false);
            $domains=$this->resellerApi->domain->lists();
            $domainlist=$domains->getResponse();
            if(!empty($domainlist)){
                $domain=$domainlist[0];
                $domainname=$domain->name;
                $data['domain'] =$domainname;
                $response   = $this->resellerApi->dnsManage->editMxSettings($data);
                $response   = $this->resellerApi->dnsManage->editMxSettingsInternal($data);
            }else{
                $response=false;
            }

        }else{
            $domain = $this->getRequestValue('domain');

            $data['domain'] =$domain;

            $this->loadUserApi();

            $response   = $this->resellerApi->dnsManage->editMxSettings($data);
            $response   = $this->resellerApi->dnsManage->editMxSettingsInternal($data);

        }

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('mxTemplateSet');
    }
    public function delete()
    {
        parent::delete();

        $this->formData['domain'] = $this->getRequestValue('domain');
        $this->userApi->dnsManage->deleteRecord(new DnsManage($this->formData));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('dnsRecordDeleted');
    }
    public function update()
    {
        parent::update();

        $oldValue = $this->formData['typeTmp'] == 'TXT' ? '"' . $this->formData['oldValue'] . '"' : $this->formData['oldValue'];

        $data = [
            'ttl' => $this->formData['ttl'],
            'value' => $this->formData['typeTmp'] == 'MX' ? $this->formData['mxNumeric'] : $this->formData['value'],
            'mxValue' => $this->formData['typeTmp'] == 'MX' ? $this->formData['mxValue'] : '',
            'name' => $this->formData['name'],
            'domain' => $this->getRequestValue('domain'),
            'type' => $this->formData['typeTmp'],
            'oldValue' => $oldValue,
            'oldName' => $this->formData['oldName']
        ];

        $this->userApi->dnsManage->editRecord(new DnsManage($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('dnsRecordModified');
    }

    public function massDelete()
    {
        parent::delete();
        $data = [];

        $dnsRecords = $this->getRequestValue('massActions', []);

        foreach ($dnsRecords as $key => $value) {

            $record =  json_decode(base64_decode($value));

            // if value contain space it must be surrounded with ""
            $data[$record->type][] = [
                'value' => $record->value,
                'name' => $record->name,
                'type' => $record->type,
                'domain' => $this->getRequestValue('domain')
            ];

        }
        $this->userApi->dnsManage->deleteMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('dnsRecordDeletedMany');
    }
}
