<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\FloatingIP\Providers;

use ModulesGarden\Servers\HetznerVps\App\UI\FloatingIP\Helpers\FloatingIPManager;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

/**
 * Description of Rebuild
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class FloatingIPProvider extends BaseDataProvider implements ClientArea, AdminArea
{

    public function read()
    {
        if (!$this->actionElementId) {
            return;
        }
        $this->data['id'] = $this->actionElementId;
        $manager = new FloatingIPManager($this->getWhmcsParams());
        $entity = $manager->read($this->data['id']);
        $this->data['dns'] = $entity->dnsPtr[0]->dns_ptr;
    }

    public function create()
    {
    }

    public function delete()
    {
    }

    public function update()
    {
        try {
            $manager = new FloatingIPManager($this->getWhmcsParams());
            $entity = $manager->read($this->formData['id']);

            $entity->changeReverseDNS($entity->ip, $this->formData['dns']);
            sl("lang")->addReplacementConstant("description", $this->formData['description']);
            return (new HtmlDataJsonResponse())
                ->setStatusSuccess()
                ->setMessageAndTranslate('updateFloatingIP');
        } catch (\Exception $ex) {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }
}
