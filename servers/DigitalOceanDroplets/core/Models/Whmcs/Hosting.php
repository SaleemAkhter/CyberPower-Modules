<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs;

use \Illuminate\Database\Eloquent\model as EloquentModel;

/**
 * Description of Product
 *
 * @author Paweł Złamaniec <pawel.zl@modulesgarden.com>
 * 
 */
class Hosting extends EloquentModel
{

    /**
     * Table name
     *
     * @var string
     */
    protected $table      = 'tblhosting';
    protected $primaryKey = 'id';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['userid', 'orderid', 'packageid', 'server', 'regdate', 'domain', 'paymentmethod', 'firstpaymentamount', 'amount', 'billingcycle', 'nextduedate', 'nextinvoicedate', 'termination_date', 'completed_date', 'domainstatus', 'username', 'password', 'notes', 'subscriptionid', 'promoid', 'suspendreason', 'overideautosuspend', 'overidesuspenduntil', 'dedicatedip', 'assignedips', 'ns1', 'ns2', 'diskusage', 'disklimit', 'bwusage', 'bwlimit', 'lastupdate'];

    /**
     * Indicates if the model should soft delete.
     *
     * @var bool
     */
    protected $softDelete = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Get related product
     *
     * @return type
     */
    public function product()
    {
        return $this->belongsTo("ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Product", "packageid");
    }

    public function addons()
    {
        return $this->hasMany("ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\HostingAddon", "hostingid");
    }

    public function client()
    {
        return $this->belongsTo("ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Client", "userid");
    }

    public function configOptions()
    {
        return $this->hasMany("ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\HostingConfigOption", "relid");
    }

    public function server()
    {
        return $this->belongsTo(Servers::class, "server");
    }

    public function servers()
    {
        return $this->hasOne(Servers::class, "id", "server");
    }

    public function order()
    {
        return $this->belongsTo("ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Order", "orderid");
    }

    public function cancelation()
    {
        return $this->hasOne("ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\CancelRequest", "relid");
    }

    public function getBillingcycleFriendlyAttribute()
    {
        return $this->attributes['billingcycle'];
    }

}
