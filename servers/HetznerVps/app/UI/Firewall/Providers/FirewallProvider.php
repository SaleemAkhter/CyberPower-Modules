<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Providers;

use Exception;
use LKDev\HetznerCloud\Models\Firewalls\Firewall;
use LKDev\HetznerCloud\Models\Firewalls\FirewallResource;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

class FirewallProvider extends BaseDataProvider implements ClientArea, AdminArea
{
    /**
     * @var Api
     */
    private $api;

    public function read()
    {
        $this->data['id'] = $this->actionElementId;
        $this->data['name'] = 'Firewall-'.$this->getWhmcsParams()['domain'];
    }

    public function update()
    {

    }

    public function create()
    {
        try {
            $this->connectWithApi();
            $server = $this->api->servers()->getById($this->getWhmcsParams()['customfields']['serverID']);
            foreach ($server->publicNet->firewalls as $firewall) {
                $firewall = $this->api->firewalls()->getById($firewall->id);
                if ($firewall->name == $this->formData['name']) {
                    return (new HtmlDataJsonResponse())->setStatusError()->setMessageAndTranslate('nameAlreadyUsed');
                }
            }

            $this->formData['applyTo'] = ($this->formData['applyTo'] == "off") ? [] : [new FirewallResource(FirewallResource::TYPE_SERVER, $server)];
            if (empty($this->formData['rules'])) $this->formData['rules'] = [];
            if (empty($this->formData['labels'])) $this->formData['labels'] = [];
            $result = $this->api->firewalls()->create(
                $this->formData['name'],
                $this->formData['rules'],
                $this->formData['applyTo'],
                $this->formData['labels']
            );
            if ($result->error == null) {
                return (new HtmlDataJsonResponse())->setMessageAndTranslate('FirewallCreateSuccessful');
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function delete()
    {
        try {
            $this->connectWithApi();
            $firewall = new Firewall($this->formData['id']);
            $server = $this->api->servers()->getById($this->getWhmcsParams()['customfields']['serverID']);
            $firewall->removeFromResources([new FirewallResource(FirewallResource::TYPE_SERVER, $server)]);
            $result = $firewall->delete();
            if ($result == true) {
                return (new HtmlDataJsonResponse())->setMessageAndTranslate('FirewallDeleteSuccessful');
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function deleteMass()
    {
        try {
            $this->connectWithApi();
            $server = $this->api->servers()->getById($this->getWhmcsParams()['customfields']['serverID']);
            $appiledFirewalls = [];
            foreach ($server->publicNet->firewalls as $firewall) {
                $appiledFirewalls[] = $firewall->id;
            }
            foreach ($this->getMassActionsValues() as $key => $value){
                $firewall = new Firewall($value);
                if (in_array($value, $appiledFirewalls)) $firewall->removeFromResources([new FirewallResource(FirewallResource::TYPE_SERVER, $server)]);
                sleep(0.05);
                $result = $firewall->delete();
                if ($result != true){
                    return (new HtmlDataJsonResponse())->setMessageAndTranslate('MassFirewallDeleteError');
                }
            }
            return (new HtmlDataJsonResponse())->setMessageAndTranslate('FirewallMassDeleteSuccessful');

        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function applyFirewallToServer()
    {
        try {
            $this->connectWithApi();
            $firewall = new Firewall($this->formData['id']);
            $server = $this->api->servers()->getById($this->getWhmcsParams()['customfields']['serverID']);
            $result = $firewall->applyToResources([new FirewallResource(FirewallResource::TYPE_SERVER, $server)]);
            if ($result->error == null){
                return (new HtmlDataJsonResponse())->setMessageAndTranslate('ApplyFirewallSuccessful');
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function removeFromResource()
    {
        try {
            $this->connectWithApi();
            $firewall = new Firewall($this->formData['id']);
            $server = $this->api->servers()->getById($this->getWhmcsParams()['customfields']['serverID']);
            $result = $firewall->removeFromResources([new FirewallResource(FirewallResource::TYPE_SERVER, $server)]);

            if ($result->error == null){
                return (new HtmlDataJsonResponse())->setMessageAndTranslate('FirewallRemovedFromResourceSuccessfully');
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    private function connectWithApi()
    {
        $this->api = new Api($this->getWhmcsParams());
    }
}