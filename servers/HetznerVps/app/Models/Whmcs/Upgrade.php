<?php


namespace ModulesGarden\Servers\HetznerVps\App\Models\Whmcs;

/**
 * Class Upgrade
 * @package ModulesGarden\Servers\HetznerVps\App\Models\Whmcs
 * @method static $this ofHostingId($hostingId)
 * @method $this today()
 * @method $this pending()
 * @method $this ofOptionId($optionId)
 */
class Upgrade extends \ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\Upgrade
{

    public function scopeOfHostingId($query, $hostingId)
    {
        return $query->where('relid', $hostingId)
            ->where('type', "configoptions");
    }

    public function scopeToday($query)
    {
        return $query->whereRaw("DATE(`date`) = DATE(NOW())");
    }

    public function scopePending($query)
    {
        return $query->where('status', "Pending");
    }

    public function scopeOfOptionId($query, $optionId)
    {
        return $query->where('originalvalue', "like", "{$optionId}=>%");
    }
}