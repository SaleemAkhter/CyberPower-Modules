<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\FloatingIP\Pages;

use ModulesGarden\Servers\HetznerVps\App\UI\FloatingIP\Buttons\DeleteButton;
use ModulesGarden\Servers\HetznerVps\App\UI\FloatingIP\Buttons\UpdateButton;
use ModulesGarden\Servers\HetznerVps\App\UI\FloatingIP\Helpers\FloatingIPManager;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataTable;

/**
 * Description of Product
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class FloatingIPPage extends DataTable implements ClientArea, AdminArea
{

    protected $id = 'floatingIPsTable';
    protected $name = 'floatingIPsTable';
    protected $title = 'floatingIPsTable';

    public function loadHtml()
    {
        $this->addColumn((new Column('ip'))->setOrderable('ASC')->setSearchable(true));
        $this->addColumn((new Column('dns'))->setOrderable()->setSearchable(true));
    }

    public function initContent()
    {
        $this->addActionButton(new UpdateButton());
    }

    protected function loadData()
    {
        $dataManager = new FloatingIPManager($this->getwhmcsParams());
        $data = $dataManager->get();

        $dataToProvider = [];

        foreach ($data as $floatingIp) {
            $dataToProvider[] = [
                'id' => $floatingIp->id,
                'ip' => $floatingIp->ip,
                'dns' => $floatingIp->dnsPtr[0]->dns_ptr,
            ];
        }

        $provider = new ArrayDataProvider();

        $provider->setData($dataToProvider);

        $this->setDataProvider($provider);
    }

}
