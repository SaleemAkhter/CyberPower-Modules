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

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Providers;

use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\App\Enum\CustomField;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;


class BackupProvider extends BaseDataProvider implements ClientArea
{

    public function read()
    {
        if ($this->actionElementId && $this->actionElementId !='backupDataTable')
        {
            $this->data = (new InstanceFactory())->fromWhmcsParams()->backups()->find($this->actionElementId)->toArray();
        }
    }

    public function create()
    {
    }

    public function update()
    {

    }

    public function delete()
    {
        $backups = (new InstanceFactory())->fromWhmcsParams()->backups();
        $backups->findId([$this->formData['id']])
            ->delete();
        return (new HtmlDataJsonResponse())
            ->setStatusSuccess()
            ->setMessageAndTranslate('The backup has been deleted successfully');

    }

    public function deleteMass()
    {
        $backups = (new InstanceFactory())->fromWhmcsParams()->backups();
        $backups->findId($this->request->get('massActions'))
            ->delete();
        return (new HtmlDataJsonResponse())
            ->setStatusSuccess()
            ->setMessageAndTranslate('The backupss have been deleted successfully');
    }

    public function restore()
    {
        $instance = (new InstanceFactory())->fromWhmcsParams();
        $backup = $instance->backups()->find($this->formData['id']);
        $backup->setInstanceId($instance->getId());
        $backup->restore();
        return (new HtmlDataJsonResponse())
            ->setStatusSuccess()
            ->setMessageAndTranslate('The backup has been restored successfully');
    }

}