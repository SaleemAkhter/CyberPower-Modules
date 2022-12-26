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

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Firewall\Providers;

use ModulesGarden\Servers\VultrVps\App\Api\FirewallGroupFactory;
use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\App\Api\Models\FirewallGroup;
use ModulesGarden\Servers\VultrVps\App\Enum\CustomField;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;


class FirewallProvider extends BaseDataProvider implements ClientArea
{

    public function read()
    {
        if ($this->actionElementId && $this->actionElementId !='firewallDataTable')
        {
            $this->data = ['id' =>$this->actionElementId  ];
        }
        $lang = sl('lang');
        //source
        $this->availableValues['source']=['0' => $lang->abtr('Custom'), 'cloudflare' => $lang->abtr('Cloudflare')];
        //type
        $this->availableValues['type']=['v4' => $lang->abtr('v4'), 'v6' => $lang->abtr('v6')];
        //protocol
        $this->availableValues['protocol']=[
            'icmp' => $lang->abtr('icmp'),
            'tcp' => $lang->abtr('tcp'),
            'udp' => $lang->abtr('udp'),
            'gre' => $lang->abtr('gre'),
            'esp' => $lang->abtr('esp'),
            'ah' => $lang->abtr('ah'),
        ];
    }

    public function create()
    {
        if($this->formData['source'] === "0"){
            $this->formData['source'] = null;
        }
        $firewallRule = (new FirewallGroupFactory())->fromWhmcsParams()->firewallRule();
        $firewallRule->setType($this->formData['type'])
                   ->setProtocol($this->formData['protocol'])
            ->setSubnet($this->formData['subnet'])
            ->setSubnetSize($this->formData['subnet_size'])
            ->setPort($this->formData['port'])
            ->setSource($this->formData['source'])
            ->setNotes($this->formData['notes'])
            ->create();
        return (new HtmlDataJsonResponse())
            ->setStatusSuccess()
            ->setMessageAndTranslate('The firewall rule has been created successfully');
    }

    public function update()
    {
        $firewallRule = (new FirewallGroupFactory())->fromWhmcsParams()->firewallRule($this->formData['id']);
        $firewallRule->setNotes($this->formData['notes'])
                     ->update();
        return (new HtmlDataJsonResponse())
            ->setStatusSuccess()
            ->setMessageAndTranslate('The firewall rule has been updated successfully');
    }

    public function delete()
    {
        $firewallRule = (new FirewallGroupFactory())->fromWhmcsParams()->firewallRule($this->formData['id']);
        $firewallRule->delete();
        return (new HtmlDataJsonResponse())
            ->setStatusSuccess()
            ->setMessageAndTranslate('The firewall rule has been deleted successfully');
    }

    public function deleteMass()
    {
        $firewalGroup =  (new FirewallGroupFactory())->fromWhmcsParams();
        foreach ($this->request->get('massActions')  as $id){
            $firewalGroup->firewallRule($id)->delete();
        }
        return (new HtmlDataJsonResponse())
            ->setStatusSuccess()
            ->setMessageAndTranslate('The firewall rules have been deleted successfully');
    }


}