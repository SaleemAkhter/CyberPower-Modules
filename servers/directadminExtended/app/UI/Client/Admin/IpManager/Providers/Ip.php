<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Ip extends ProviderApi
{

    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    public function read()
    {
        $this->loadLang();
        $this->data['iptype']                = $this->formData['iptype'];
        $this->availableValues['iptype']     = [
            'ipv4' => $this->lang->absoluteTranslate('ipv4'),
            'ipv6'    => $this->lang->absoluteTranslate('ipv6')
        ];
        $this->data['ip']                = $this->formData['ip'];
        $this->data['netmask']                = $this->formData['netmask'];
    }

    public function create()
    {
        parent::create();
        /*Add validation for name */

        $data = [
            'ip'      => $this->formData['ip'],
            'netmask' => $this->formData['netmask'],
            'add_to_device' => 'yes',
            'add_to_device_aware' =>'yes',
        ];

        $this->loadAdminApi();
        $response=$this->adminApi->ip->admin_add($data);



        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('ipHasBeenCreated');
    }
    public function clearns()
    {
        $this->loadAdminApi();
        $data = json_decode(base64_decode($this->actionElementId));
        $postdata = [
            'select0' => $data->ip
        ];
        return $this->adminApi->ip->admin_clearns($postdata);
    }
    public function setglobal()
    {
        $this->loadAdminApi();
        $data = json_decode(base64_decode($this->actionElementId));
        $postdata = [
            'select0' => $data->ip
        ];
        return $this->adminApi->ip->admin_setglobal($postdata);
    }
    public function unsetglobal()
    {
        $this->loadAdminApi();
        $data = json_decode(base64_decode($this->actionElementId));
        $postdata = [
            'select0' => $data->ip
        ];
        return $this->adminApi->ip->admin_unsetglobal($postdata);
    }
    public function free()
    {
        $this->loadAdminApi();
        $data = json_decode(base64_decode($this->actionElementId));
        $postdata = [
            'select0' => $data->ip
        ];
        return $this->adminApi->ip->admin_free($postdata);
    }
    public function share()
    {
        $this->loadAdminApi();
        $data = json_decode(base64_decode($this->actionElementId));
        $postdata = [
            'select0' => $data->ip
        ];

        return $this->adminApi->ip->admin_share($postdata);
    }

    public function delete()
    {
        parent::delete();
        $this->loadAdminApi();
        $data = [
            'ip' => $this->formData['ip']
        ];
        $response=$this->adminApi->ip->admin_delete($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('ipHasBeenDeleted');
    }
    
    public function deleteMany()
    {
        parent::delete();

        $data        = [];
        $ips = $this->getRequestValue('massActions', []);
        $this->loadLang();

        foreach ($ips as $ip)
        {
            $ipdata = json_decode(base64_decode($ip));
            $data[] = $ipdata->ip;
        }
        $this->loadAdminApi();
        $this->adminApi->ip->admin_deleteMany($data);



        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('ipsHaveBeenDeleted');
    }

    public function update()
    {

    }

    public function reload()
    {

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
