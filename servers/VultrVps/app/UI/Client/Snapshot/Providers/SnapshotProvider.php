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

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Providers;

use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\App\Enum\CustomField;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;


class SnapshotProvider extends BaseDataProvider implements ClientArea
{

    public function read()
    {
        if ($this->actionElementId && $this->actionElementId !='snapshotDataTable')
        {
            $this->data = (new InstanceFactory())->fromWhmcsParams()->snapshots()->find($this->actionElementId)->toArray();
        }
    }

    public function create()
    {
        $snapshot = (new InstanceFactory())->fromWhmcsParams()->snapshot();
        $snapshot->setDescription(sprintf("%s#%s",$this->getWhmcsCustomField(CustomField::INSTANCE_ID),$this->formData['description']))
            ->create();
        return (new HtmlDataJsonResponse())
            ->setStatusSuccess()
            ->setMessageAndTranslate('The snapshot has been created successfully');
    }

    public function update()
    {
        $snapshot = (new InstanceFactory())->fromWhmcsParams()->snapshots()->find($this->formData['id']);
        $snapshot->setDescription(sprintf("%s#%s",$this->getWhmcsCustomField(CustomField::INSTANCE_ID),$this->formData['description']))
                  ->update();
        return (new HtmlDataJsonResponse())
            ->setStatusSuccess()
            ->setMessageAndTranslate('The snapshot has been updated successfully');
    }

    public function delete()
    {
        $snapshots = (new InstanceFactory())->fromWhmcsParams()->snapshots();
        $snapshots->findId([$this->formData['id']])
                 ->delete();
        return (new HtmlDataJsonResponse())
            ->setStatusSuccess()
            ->setMessageAndTranslate('The snapshot has been deleted successfully');
    }

    public function deleteMass()
    {
        $snapshots = (new InstanceFactory())->fromWhmcsParams()->snapshots();
        $snapshots->findId($this->request->get('massActions'))
                  ->delete();
        return (new HtmlDataJsonResponse())
            ->setStatusSuccess()
            ->setMessageAndTranslate('The snapshots have been deleted successfully');
    }

    public function restore()
    {
        $snapshot = (new InstanceFactory())->fromWhmcsParams()->snapshots()->find($this->formData['id']);
        $snapshot->restore();
        return (new HtmlDataJsonResponse())
            ->setStatusSuccess()
            ->setMessageAndTranslate('The snapshot has been restored successfully');
    }

}