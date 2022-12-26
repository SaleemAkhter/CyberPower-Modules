<?php

/* * ********************************************************************
 * Servers\HetznerVps product developed. (Jan 17, 2019)
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

namespace ModulesGarden\Servers\HetznerVps\App\Models\Whmcs;


/**
 * Description of Hosting
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 * @property int $id
 * @property int $userid
 * @property int $orderid
 * @property int $packageid
 * @property int $server
 * @property string $regdate
 * @property string $domain
 * @property string $paymentmethod
 * @property float $firstpaymentamount
 * @property float $amount
 * @property string $billingcycle
 * @property string $nextduedate
 * @property string $nextinvoicedate
 * @property string $termination_date
 * @property string $completed_date
 * @property string $domainstatus
 * @property string $username
 * @property string $password
 * @property string $notes
 * @property string $subscriptionid
 * @property int $promoid
 * @property string $suspendreason
 * @property int $overideautosuspend
 * @property string $overidesuspenduntil
 * @property string $dedicatedip
 * @property string $assignedips
 * @property string $ns1
 * @property string $ns2
 * @property int $diskusage
 * @property int $disklimit
 * @property int $bwusage
 * @property int $bwlimit
 * @property string $lastupdate
 * @property string $created_at
 * @property string $updated_at
 * @method Hosting active()
 * @method Hosting activeAndSuspended
 * @method Hosting ofUserId($userId)
 * @method Hosting ofServerId($serverId)
 * @method Hosting  ofCustomFieldNode($node)
 * @method Hosting  ofvServerNode($node)
 * @method Hosting  ofCustomFielVmid($vmid)
 * @method Hosting  ofvServerVmid($vmid)
 * @method Hosting  ofStatus($status)
 * @method static Hosting ofServerType($hostingId, $moduleName)
 * @method static Hosting  ofId($id)
 * @method Hosting[] get()
 * @property CustomFieldValue[] $customFieldValues
 * @method static Hosting ofHetznerVps()
 * @method static Hosting ofProductId($id)
 */
class Hosting extends \ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\Hosting
{

    public function scopeActive($query)
    {
        return $query->where('domainstatus', 'Active');
    }

    public function scopeActiveAndSuspended($query)
    {
        return $query->whereIn('domainstatus', ['Active', 'Suspended']);
    }

    public function scopeOfId($query, $id)
    {
        return $query->where('id', $id);
    }

    public function scopeOfServerId($query, $serverId)
    {
        return $query->where('server', $serverId);
    }

    public function scopeOfUserId($query, $userId)
    {
        return $query->where('userid', $userId);
    }

    public function scopeOfCustomFieldNode($query, $node)
    {
        $h   = 'tblhosting';
        $cf  = 'tblcustomfields';
        $cfv = 'tblcustomfieldsvalues';
        $query->select("{$h}.*")
            ->rightJoin($cfv, "{$cfv}.relid", '=', "{$h}.id")
            ->rightJoin($cf, "{$cfv}.fieldid", '=', "{$cf}.id")
            ->where("{$cf}.type", "product")
            ->where("{$cf}.fieldname", "LIKE", "node%")
            ->where("{$cfv}.value", $node);
        return $query;
    }

    public function scopeOfCustomFielVmid($query, $vmid)
    {
        $h   = 'tblhosting';
        $cf  = 'tblcustomfields';
        $cfv = 'tblcustomfieldsvalues';
        $query->select("{$h}.*")
            ->rightJoin($cfv, "{$cfv}.relid", '=', "{$h}.id")
            ->rightJoin($cf, "{$cfv}.fieldid", '=', "{$cf}.id")
            ->where("{$cf}.type", "product")
            ->where("{$cf}.fieldname", "LIKE", "vmid%")
            ->where("{$cfv}.value", $vmid);
        return $query;
    }

    public function scopeOfvServerNode($query, $node)
    {
        $h  = 'tblhosting';
        $vs = 'Servers\HetznerVps_Vm';
        $query->select("{$h}.*")
            ->rightJoin($vs, "{$vs}.hosting_id", '=', "{$h}.id")
            ->where("{$vs}.node", $node);
        return $query;
    }

    public function scopeOfvServerVmid($query, $vmid)
    {
        $h  = 'tblhosting';
        $vs = 'Servers\HetznerVps_Vm';
        $query->select("{$h}.*")
            ->rightJoin($vs, "{$vs}.hosting_id", '=', "{$h}.id")
            ->where("{$vs}.vmid", $vmid);
        return $query;
    }

    public function scopeOfStatus($query, $status)
    {
        return $query->whereIn('domainstatus', $status);
    }

    public function scopeOfServerType($query, $hostingId, $moduleName)
    {
        $h = "tblhosting";
        $p = "tblproducts";
        $query->select("{$h}.*");
        return $query->rightJoin($p, function ($join) use ($p)
        {
            $join->on('packageid', '=', "{$p}.id");
        })->where("{$h}.id", $hostingId)
            ->where("{$p}.servertype", $moduleName);
    }

    public function ipAdd($ip)
    {
        if (!$this->dedicatedip)
        {
            $this->dedicatedip = $ip;
            return $this;
        }
        $ips               = explode("\n", $this->assignedips);
        $ips[]             = $ip;
        $ips               = array_unique($ips);
        $this->assignedips = implode("\n", $ips);
        return $this;
    }

    public function ipDelete($ip)
    {
        if ($this->dedicatedip == $ip)
        {
            $this->dedicatedip = null;
            return $this;
        }
        $ips = explode("\n", $this->assignedips);
        foreach ($ips as $k => $v)
        {
            if (trim($v) == trim($ip))
            {
                unset($ips[$k]);
            }
        }
        $this->assignedips = implode("\n", $ips);
        return $this;
    }

    /**
     * @return \DateTime
     * @throws \Exception
     */
    public function getLastUpdate()
    {
        return new \DateTime($this->lastupdate);
    }

    public function customFieldValues()
    {
        return $this->hasMany("ModulesGarden\Servers\HetznerVps\App\Models\Whmcs\CustomFieldValue", "relid");
    }

    public function scopeOfHetznerVps($query)
    {
        $h = 'tblhosting';
        $s = 'tblservers';
        $query->select("{$h}.id", "{$h}.packageid", "{$h}.lastupdate", "{$h}.bwusage", "{$h}.bwlimit", "{$h}.regdate")
            ->rightJoin($s, "{$h}.server", '=', "{$s}.id")
            ->where("{$s}.type", "hetznerVPS");
        return $query;
    }

    public function isBandwidthOverageUsage()
    {
        return $this->bwlimit && $this->bwlimit < $this->bwusage;
    }

    public function scopeOfProductId($query, $id){
        return $query->where('packageid', $id);
    }

}
