<?php
/* * ********************************************************************
*  HetznerVPS Product developed. (27.03.19)
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

namespace ModulesGarden\Servers\HetznerVps\App\Service\Sidebar;

use ModulesGarden\Servers\HetznerVps\App\Models\Whmcs\CustomField;
use ModulesGarden\Servers\HetznerVps\App\Models\Whmcs\CustomFieldValue;
use ModulesGarden\Servers\HetznerVps\App\Models\Whmcs\Hosting;
use ModulesGarden\Servers\HetznerVps\App\Service\Utility;
use ModulesGarden\Servers\HetznerVps\App\Enum\ConfigurableOption;

trait HostingService
{

    /**
     * @return mixed
     */
    public function getHostingId()
    {
        if (!$this->hostingId)
        {
            $this->hostingId = $this->getWhmcsParamByKey("serviceid");
        }
        return $this->hostingId;
    }

    /**
     * @param mixed $hostingId
     */
    public function setHostingId($hostingId)
    {
        $this->hostingId = $hostingId;
        return $this;
    }

    /**
     * @return Hosting
     */
    public function hosting()
    {
        if ($this->hosting instanceof Hosting)
        {
            return $this->hosting;
        }
        return $this->hosting = Hosting::where("id", $this->getHostingId())->firstOrFail();
    }

    public function isActive()
    {
        return Hosting::ofId($this->getHostingId())->active()->count() == 1;
    }

    public function isSupportedModule()
    {
        return Hosting::ofServerType($this->getHostingId(), "hetznerVPS")->count() == 1;
    }

    private function getCustomFieldId($fieldName)
    {
        return CustomField::select("id")
            ->where("type", "product")
            ->where("relid", $this->hosting()->packageid)
            ->where("fieldname", "like", $fieldName . "%")
            ->value("id");
    }

    public function customFieldUpdate($name, $value = '')
    {
        $fieldId = $this->getCustomFieldId($name);
        //Update
        if (CustomFieldValue::where('fieldid', $fieldId)->where("relid", $this->getHostingId())->count())
        {
            return CustomFieldValue::where('fieldid', $fieldId)->where("relid", $this->getHostingId())->update(['value' => $value]);
        }
        //Create
        $customFiledValue = new CustomFieldValue();
        $customFiledValue->fill([
            'fieldid' => $fieldId,
            'relid'   => $this->getHostingId(),
            'value'   => $value
        ]);
        return $customFiledValue->save();
    }

    public function saveUsageLimit()
    {
        //bandwidth
        if ($this->getWhmcsConfigOption(ConfigurableOption::BANDWIDTH))
        {
            $bandwidthMb = $this->getWhmcsConfigOption(ConfigurableOption::BANDWIDTH);
            Utility::unitFormat($bandwidthMb, "gb", 'mb');
        }
        else
        {
            if ($this->getWhmcsConfigOption('Bandwidth Limit'))
            {
                $bandwidthMb = $this->getWhmcsConfigOption('Bandwidth Limit');
                Utility::unitFormat($bandwidthMb, "gb", 'mb');
            }
            else
            {
                if ($this->configuration()->getBandwidth())
                {
                    $bandwidthMb = $this->configuration()->getBandwidth();
                    Utility::unitFormat($bandwidthMb, "gb", 'mb');
                }
            }
        }
        //disk
        $diskMb = 0;
        if ($this->getWhmcsConfigOption(ConfigurableOption::DISK_SIZE))
        {
            $diskMb = $this->getWhmcsConfigOption(ConfigurableOption::DISK_SIZE);
            Utility::unitFormat($diskMb, $this->configuration()->getDiskUnit(), 'mb');
        }
        else
        {
            if ($this->configuration()->getDiskSize())
            {
                $diskMb = $this->configuration()->getDiskSize();
                Utility::unitFormat($diskMb, "gb", 'mb');
            }
        }
        $this->hosting()->update(['disklimit' => $diskMb, "bwlimit" => $bandwidthMb, "lastupdate" => date("Y-m-d H:i:s")]);
    }


    public function isBandwidthOverageUsage()
    {

    }
}