<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use Mso\IdnaConvert\IdnaConvert;

class Ssl extends ProviderApi
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
    public function read()
    {
        $this->loadLang();
        $this->availableValues['domains']   = $this->getDomainList();

        $this->availableValues['size']  = [
            '2048'  => $this->lang->absoluteTranslate('2048bits'),
            '4096'  => $this->lang->absoluteTranslate('4096bits'),
        ];


        $this->data['encryption']                = [];
        $this->availableValues['encryption']     = [
            'sha256'  => $this->lang->absoluteTranslate('SHA256'),
            'sha1'    => $this->lang->absoluteTranslate('SHA1'),
        ];

        if(empty($this->formData))
        {
            $domain = $this->getWhmcsParamByKey('domain');
            $this->data['entries_' . $domain] = 'on';
            $this->data['entries_www.' . $domain] = 'on';
        }
        else
        {
            $this->data['entries_' . $this->formData['domains']] = 'on';
            $this->data['entries_www.' . $this->formData['domains']] = 'on';
        }
    }

    public function create()
    {
        parent::create();

        $data = [
            'type'      => __FUNCTION__,
            'domain'    => $this->formData['domains'],
            'country'   => $this->formData['code'],
            'company'   => $this->formData['company'],
            'division'  => $this->formData['division'],
            'email'     => $this->formData['email'],
            'city'      => $this->formData['city'],
            'keysize'   => $this->formData['size'],
            'name'      => $this->formData['name'],
            'province'  => $this->formData['state']
        ];
        $this->userApi->ssl->save(new Models\Command\Ssl($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setMessageAndTranslate('certificateHasBeenGenerated')
            ->setRefreshTargetIds(['privateKeysTable', 'certificatesTable']);

    }

    public function generate()
    {
        parent::create();

        $data = [
            'type'      => 'create',
            'domain'    => $this->formData['domains'],
            'country'   => $this->formData['code'],
            'company'   => $this->formData['company'],
            'division'  => $this->formData['division'],
            'email'     => $this->formData['email'],
            'city'      => $this->formData['city'],
            'keysize'   => $this->formData['size'],
            'province'  => $this->formData['state'],
            'name'      => $this->formData['name'],
            'request'   => 'yes'
        ];
        $response    = $this->userApi->ssl->save(new Models\Command\Ssl($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setMessageAndTranslate('certificateHasBeenGenerated')
            ->addData('csr', $response['request'])
            ->setCallBackFunction('showCsr');

    }


    public function upload(){

        parent::create();

        $idna  = new IdnaConvert();

        $data = [
            'domain'    => $this->formData['domains'],
            'certificate' =>   $idna->encode($this->formData['privateKey']). $idna->encode($this->formData['certificate'])
        ];

        $this->userApi->ssl->upload(new Models\Command\Ssl($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setMessageAndTranslate('certificateHasBeenUploaded')
            ->setRefreshTargetIds(['privateKeysTable', 'certificatesTable']);

    }
    public function letsEncrypt(){
        parent::create();

        if(!in_array('on', $this->formData))
        {
            $this->loadLang();
            return (new ResponseTemplates\RawDataJsonResponse())
                ->setStatusError()
                ->setMessage($this->lang->absoluteTranslate('noEntriesSelected'));
        }

        $data = [
            'type'      => 'create',
            'domain'    => $this->formData['domains'],
            'wildcard'  => $this->formData['wildcard'] == 'on' ? 'yes' : 'no',
            'request'   => 'letsencrypt',
            'company'   => $this->formData['company'],
            'email'     => $this->formData['email'],
            'keysize'   => $this->formData['size'],
            'name'      => $this->formData['domains'],
            'encryption'  => $this->formData['encryption'],
            'entries' => $this->getEntriesArray()
        ];

        $this->userApi->ssl->letsEncrypt(new Models\Command\Ssl($data));
        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setMessageAndTranslate('certificateHasBeenEncrypted')
            ->setRefreshTargetIds(['privateKeysTable', 'certificatesTable']);
    }

    public function getEntriesArray(){
        $entries = [];
            foreach($this->formData as $key => $fieldValue)
            {
                if(strpos($key, 'entries_' ) === false)
                {
                    continue;
                }

                if($fieldValue == "on"){
                    $explodeEntries = explode('_', $key);
                    if(empty($explodeEntries[1])){
                        $entries[] = $this->formData['domains'];
                        continue;
                    }
                    $entries[] = $explodeEntries[1];
                }
            }
        return $entries;
    }

    public function reload()
    {
        $this->data['domains']      = $this->formData['domains'];
        $this->data['email']        = $this->formData['email'];
        $this->data['size']         = $this->formData['size'];
        $this->data['encryption']   = $this->formData['encryption'];
    }

    public function loadSwitchers($selectedDomain)
    {
        $data = [
            'domain' => $selectedDomain
        ];

        parent::read();

        $subdomains = $this->userApi->ssl->loadAvailableOptions(new Models\Command\Ssl($data));

        return array_keys($subdomains);
    }

}
