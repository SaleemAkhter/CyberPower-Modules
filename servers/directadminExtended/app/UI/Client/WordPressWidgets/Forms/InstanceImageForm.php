<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Forms;


use ModulesGarden\WordpressManager\App\Repositories\HostingRepository;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Select;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;


class InstanceImageForm extends \ModulesGarden\WordpressManager\App\UI\Installations\Forms\InstanceImageForm
{
    public function initContent()
    {
        $hostingId = di('request')->get('id');
        $repository = new HostingRepository();
        $this->request = \ModulesGarden\Servers\DirectAdminExtended\App\Helpers\WordPressManager::replaceRequestClass([
            'hostingId' => $hostingId
        ]);

        parent::initContent();

        $hostings   = $repository->findEnabledWithProduct($this->request->getSession('uid'), $hostingId);
        $field = $this->fields['hostingId'];
        if ($field instanceof Select) {
            $field->setAvailableValues((array)$hostings);
        } else {
            $field->setDefaultValue($hostingId);
        }
    }
}