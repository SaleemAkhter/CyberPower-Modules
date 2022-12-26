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
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;


class BackupScheduleProvider extends BaseDataProvider implements ClientArea
{


    public function read()
    {
        //type
        $this->availableValues['type'] = [
            'daily' => sl('lang')->abtr("Daily"),
            'daily_alt_even' => sl('lang')->abtr("Every Other Day"),
            'weekly' => sl('lang')->abtr("Weekly"),
            'monthly' => sl('lang')->abtr("Monthly"),
            //'daily_alt_odd => sl('lang')->abtr("daily_alt_odd "),
        ];
        //hour
        $this->availableValues['hour']=[];
        for($i=0; $i<=23; $i++){
            $this->availableValues['hour'][$i] = sl('lang')->abtr('serverCA','backup',sprintf("%s:00 UTC", $i));
        }
        //dow
        $this->availableValues['dow']= [
            0 => sl('lang')->abtr('serverCA','backup',"Sunday"),
            1 => sl('lang')->abtr('serverCA','backup',"Monday"),
            2 => sl('lang')->abtr('serverCA','backup',"Tuesday"),
            3 => sl('lang')->abtr('serverCA','backup',"Wednesday"),
            4 => sl('lang')->abtr('serverCA','backup',"Thursday"),
            5 => sl('lang')->abtr('serverCA','backup',"Friday"),
            6 => sl('lang')->abtr('serverCA','backup',"Saturday"),
        ];
        //dom
        $this->availableValues['dom']=[];
        for($i=1; $i<=28; $i++){
            $this->availableValues['dom'][$i] = sl('lang')->abtr('serverCA','backup',sprintf("%s Day", $i));
        }
        $backupSchedule = (new InstanceFactory())->fromWhmcsParams()->backupSchedule();
        $backupSchedule->details();
        sl('lang')->addReplacementConstant("nextScheduledTimeUtc", $backupSchedule ->getNextScheduledTimeUtc());
        if($this->request->get('formData')['type']){
            $this->data = $this->request->get('formData');
            return;
        }
        $this->data =     $backupSchedule ->toArray();

    }

    public function create()
    {
    }

    public function update()
    {
        $backupSchedule = (new InstanceFactory())->fromWhmcsParams()->backupSchedule();
        $backupSchedule->setType($this->formData['type'])
                       ->setHour($this->formData['hour'])
                       ->setDow($this->formData['dow'])
                       ->setDom($this->formData['dom'])
                       ->update();
    }

    public function delete()
    {

    }

    public function deleteMass()
    {

    }


}