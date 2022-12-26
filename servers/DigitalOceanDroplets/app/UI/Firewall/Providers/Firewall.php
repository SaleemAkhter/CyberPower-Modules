<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Providers;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Helpers\FirewallManager;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Helpers\SnapshotManager;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of Rebuild
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Firewall extends BaseDataProvider implements ClientArea, AdminArea
{

    public function read()
    {
        $this->data['id'] = $this->actionElementId;
    }

    public function create()
    {
        try
        {
            
            $pattern = '/^[a-zA-Z0-9_]+([-.][a-zA-Z0-9_]+)*$/';
            if(!preg_match($pattern, $this->formData['firewallName']))
            {
                return (new HtmlDataJsonResponse())->setStatusError()->setMessageAndTranslate('invalidName');
            }
            
            
            $snapshotManager = new FirewallManager($this->whmcsParams);
            $snapshotManager->createFirewall($this->formData['firewallName']);
            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('createFirewallSuccess');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    public function delete()
    {
        try
        {

            $snapshotManager = new FirewallManager($this->whmcsParams);
            $snapshotManager->deleteFirewall($this->formData['id']);
            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('deleteFirewall');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    public function update()
    {

        try
        {
            $snapshotManager = new SnapshotManager($this->whmcsParams);
            $snapshotManager->restoreFromSnapshot($this->formData['id']);
            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('restoreFromSnapshotStart');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

}
