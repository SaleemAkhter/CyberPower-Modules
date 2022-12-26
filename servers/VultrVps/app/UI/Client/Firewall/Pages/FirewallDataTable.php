<?php
/* * ********************************************************************
*  VultrVps Product developed. (26.03.19)
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

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Firewall\Pages;

use ModulesGarden\Servers\VultrVps\App\Api\FirewallGroupFactory;
use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\App\Helpers\Format;
use ModulesGarden\Servers\VultrVps\App\Traits\ApiClient;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Firewall\Buttons\CreateButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Firewall\Buttons\DeleteButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Firewall\Buttons\DeleteMassButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Firewall\Buttons\MailtoSwitchButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Firewall\Buttons\RestoreButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Firewall\Buttons\UpdateButton;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\DataTable\DataTable;
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;

class FirewallDataTable extends DataTable implements ClientArea
{

    use ApiClient;
    protected $id = 'firewallDataTable';
    protected $title = 'firewallDataTable';

    public function initContent()
    {
        //Create
        $this->addButton(new CreateButton());
        /*Update error: Method not allowed. Must be one of: GET, DELETE
        $this->addActionButton(new UpdateButton());
        */
        //Delete
        $this->addActionButton(new DeleteButton());
        //Mass delete
        $this->addMassActionButton(new DeleteMassButton());
    }

    protected function loadHtml()
    {
        $this->addColumn((new Column('action'))->setSearchable(true, "string")->setOrderable())
            ->addColumn((new Column('type'))->setSearchable(true, "string")->setOrderable())
            ->addColumn((new Column('protocol'))->setSearchable(true, "string")->setOrderable('ASC'))
             ->addColumn((new Column('port'))->setSearchable(true)->setOrderable())
         /*   ->addColumn((new Column('source'))->setSearchable(true, "string")->setOrderable()) */
            ->addColumn((new Column('subnet'))->setSearchable(true, "string")->setOrderable())
             ->addColumn((new Column('notes'))->setSearchable(true, "string")->setOrderable());

    }
    public function replaceFieldSubnet($key, $row)
    {
        if($row['subnet']){
            return $row['subnet'];
        }
        return "-";
    }

    public function replaceFieldType($key, $row)
    {
        return sl('lang')->abtr($row['type']);

    }

    public function replaceFieldProtocol($key, $row)
    {
        return sl('lang')->abtr($row['protocol']);

    }

    public function replaceFieldPort($key, $row)
    {
        if($row['port']){
            return $row['port'];
        }
        return "-";
    }

    public function replaceFieldSource($key, $row)
    {
        if($row['source']){
            return $row['source'];
        }
        return "-";
    }

    public function replaceFieldNotes($key, $row)
    {
        if($row['notes']){
            return $row['notes'];
        }
        return "-";
    }

    protected function loadData()
    {
        $data = [];
        $firewall = (new FirewallGroupFactory())->fromWhmcsParams();
        foreach ( $firewall->firewallRules() as $entity)
        {
            $row =  $entity->toArray();
            if($row['source']=='cloudflare'){
                $row['subnet'] = $row['source'];
            }else if($row['subnet']){
                $row['subnet'] .= "/".$row['subnetSize'];
            }
            $data[] = $row;
        }
        $dataProv = new ArrayDataProvider();
        $dataProv->setDefaultSorting("protocol", 'ASC');
        $dataProv->setData($data);
        $this->setDataProvider($dataProv);
    }

}