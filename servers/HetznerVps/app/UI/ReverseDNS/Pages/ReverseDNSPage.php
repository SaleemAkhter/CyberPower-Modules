<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Pages;

use ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Buttons\AddReverseDNSButton;
use ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Buttons\DeleteReverseDNSButton;
use ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Buttons\ReverseDNSEditButton;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataTable;
use ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Helpers\ReverseDNSMenager;

class ReverseDNSPage extends DataTable implements ClientArea, AdminArea
{
    protected $id    = 'reverseDNSTable';
    protected $name  = 'reverseDNSTable';
    protected $title = 'reverseDNSTable';

    public function loadHtml()
    {
        $this  ->addColumn((new Column('ipv'))->setOrderable('ASC')->setSearchable(true))
            ->addColumn((new Column('ip'))->setOrderable()->setSearchable(true))
            ->addColumn((new Column('dns'))->setOrderable()->setSearchable(true));
    }

    public function initContent()
    {
        $this->addButton(new AddReverseDNSButton())
            ->addActionButton(new ReverseDNSEditButton())
            ->addActionButton(new DeleteReverseDNSButton());
    }

    public function loadData()
    {
        $dataMenager = new ReverseDNSMenager($this->getWhmcsParams());
        $data = $dataMenager->get();
        $publicNet = (array)$data->publicNet;

        $dataToProvider = [];

        foreach ($publicNet as $ipv => $value)
        {
            if ($ipv != 'ipv4' && $ipv != 'ipv6')
            {
                continue;
            }

            if ($ipv == 'ipv6')
            {
                foreach ($value->dns_ptr as $ip)
                {
                    $dataToProvider[] = [
                        'ipv' => $ipv,
                        'ip' => $ip->ip,
                        'dns' => $ip->dns_ptr,
                        'id' => json_encode((array)$ip),
                    ];
                }
                continue;
            }

            $dataToProvider[] = [
                'ipv' => $ipv,
                'ip' => $value->ip,
                'dns' => $value->dns_ptr,
                'id' => json_encode((array)$value),
            ];
        }

        $provider = new ArrayDataProvider();

        $provider->setData($dataToProvider);

        $this->setDataProvider($provider);

    }

    public function replaceFieldIpv($key, $row)
    {
        return str_replace('ip','IP',$row['ipv']);
    }
}
