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

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\ChangeOs\Pages;

use ModulesGarden\Servers\VultrVps\App\Api\ApiClientFactory;
use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\App\Helpers\Format;
use ModulesGarden\Servers\VultrVps\App\Traits\ProductSetting;
use ModulesGarden\Servers\VultrVps\App\UI\Client\ChangeOs\Buttons\CreateButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\ChangeOs\Buttons\DeleteButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\ChangeOs\Buttons\DeleteMassButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\ChangeOs\Buttons\MailtoSwitchButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\ChangeOs\Buttons\ChangeOsButton;
use ModulesGarden\Servers\VultrVps\App\UI\Client\ChangeOs\Buttons\UpdateButton;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\DataTable\DataTable;
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;

class ChangeOsDataTable extends DataTable implements ClientArea
{
    protected $id = 'changeOsDataTable';
    protected $title = 'changeOsDataTable';

    use ProductSetting;

    public function initContent()
    {
        //Change OS
        $this->addActionButton(new ChangeOsButton());
    }

    protected function loadHtml()
    {
        $this->addColumn((new Column('name'))->setSearchable(true, "string")->setOrderable("ASC"));

    }


    protected function loadData()
    {
        $data = [];
        $apiClient = (new ApiClientFactory())->fromWhmcsParams();
        $osRepository = $apiClient->os();
        $osRepository->findNotName(['Snapshot','Custom','Backup','Application']);
        foreach ($osRepository->get() as $entity)
        {
            if($this->productSetting()->changeOsId && !in_array($entity->id, $this->productSetting()->changeOsId)){
                continue;
            }
            $data[]      = ['id' => base64_encode(json_encode((array)$entity)) ,'name' => $entity->name];
        }
        $dataProv = new ArrayDataProvider();
        $dataProv->setDefaultSorting("name", 'ASC');
        $dataProv->setData($data);
        $this->setDataProvider($dataProv);
    }

}