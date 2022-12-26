<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\CreateReseller\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Reseller extends ProviderApi
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
        $this->loadAdminApi();

        $result     = $this->adminApi->adminPackage->listResellerPackages();
        // debug($result);die();
        $packageopts=[];
        foreach($result as $items){
            $packageopts[$items['name']] = $items['name'];
        }
        $this->data['package']              = [];
        $this->data['availablepackages']   = $packageopts;
        $this->availableValues['package']   = $packageopts;
        $iplist=$this->adminApi->reseller->getIPs();
        $ips=[];
        foreach($iplist as $item){
            $title=$item['ip'];
            if($item['status']=='server'){
                $title.=" - Shared - Server";
            }if($item['status']=='shared'){
                $title.=" - Shared";
            }
            $ips[$item['ip']] = $title;
        }
        $this->data['ip']              = [];
        $this->availableValues['ip']   = $ips;

    }

    public function create()
    {
        parent::create();

        $data = [
            'username'      => $this->formData['username'],
            'email'      => $this->formData['email'],
            'password'      => $this->formData['password'],
            'password2'      => $this->formData['password'],
            'domain'      => $this->formData['domain'],
            'package'      => $this->formData['package'],
            'ip'      => $this->formData['ip'],
            'action'=>'create'
        ];

        $this->loadAdminApi();
        $response=$this->adminApi->reseller->createReseller(new Models\Command\ResellersUser($data));

        return (new ResponseTemplates\RawDataJsonResponse([
            'url' => $this->getResellersIndexURL(),
        ]))->setMessageAndTranslate('resellerHasBeenCreated')->setCallBackFunction('redirectAfterFormSubmit');
    }

    public function delete()
    {
        parent::delete();

        $data = [
            'user' => $this->formData['user']
        ];
         $this->loadResellerApi();
         $response=$this->resellerApi->reseller->delete(new Models\Command\User($data));
        debug($response);
        die();
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('userHasBeenDeleted');
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

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('userssHaveBeenDeleted');
    }

    public function update()
    {

    }

    public function reload()
    {
        $this->data['user']='dj3';
        $this->data['id']='dj3';
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
        $this->loadResellerApi();
        $user = $this->resellerApi->reseller->config(new Models\Command\User([
                'username'  => $data['name']
            ]));
        if($user['suspended']=='no'){
            $action='suspend';
            $messagestring='singleUserSuspend';
        }else{
            $action='unsuspend';
            $messagestring='singleUserUnsuspend';
        }

        $response=$this->resellerApi->reseller->suspendUnsuspend(new Models\Command\User($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($messagestring);
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
    protected function getResellersIndexURL()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'ListReseller',
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }
}
