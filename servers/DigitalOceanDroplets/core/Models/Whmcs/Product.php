<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs;

use \Illuminate\Database\Eloquent\model as EloquentModel;

/**
 * Description of Product
 *
 * @author Paweł Złamaniec <pawel.zl@modulesgarden.com>
 * 
 * @property int $id
 * @property string $type
 * @property int $gid
 * @property string $name
 * @property string $description
 * @property int $hidden
 * @property int $showdomainoptions
 * @property int $welcomeemail
 * @property int $stockcontrol
 * @property int $qty
 * @property int $proratabilling
 * @property int $proratadate
 * @property int $proratachargenextmonth
 * @property string $paytype
 * @property int $allowqty
 * @property string $subdomain
 * @property string $autosetup
 * @property string $servertype
 * @property int $servergroup
 * @property string $configoption1
 * @property string $configoption2
 * @property string $configoption3
 * @property string $configoption4
 * @property string $configoption5
 * @property string $configoption6
 * @property string $configoption7
 * @property string $configoption8
 * @property string $configoption9
 * @property string $configoption10
 * @property string $configoption11
 * @property string $configoption12
 * @property string $configoption13
 * @property string $configoption14
 * @property string $configoption15
 * @property string $configoption16
 * @property string $configoption17
 * @property string $configoption18
 * @property string $configoption19
 * @property string $configoption20
 * @property string $configoption21
 * @property string $configoption22
 * @property string $configoption23
 * @property string $configoption24
 * @property string $freedomain
 * @property string $freedomainpaymentterms
 * @property string $freedomaintlds
 * @property int $recurringcycles
 * @property int $autoterminatedays
 * @property int $autoterminateemail
 * @property int $configoptionsupgrade
 * @property string $billingcycleupgrade
 * @property int $upgradeemail
 * @property string $overagesenabled
 * @property int $overagesdisklimit
 * @property int $overagesbwlimit
 * @property float $overagesdiskprice
 * @property float $overagesbwprice
 * @property int $tax
 * @property int $affiliateonetime
 * @property string $affiliatepaytype
 * @property float $affiliatepayamount
 * @property int $order
 * @property int $retired
 * @property int $is_featured
 * @property string $created_at
 * @property string $updated_at
 */
class Product extends EloquentModel
{

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'tblproducts';
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
    protected $fillable = ['type', 'gid', 'name', 'description', 'hidden', 'showdomainoptions', 'welcomemail', 'stockcontrol', 'qty', 'proratabilling', 'proratadate', 'proratachargenextmonth', 'paytype', 'allowqty', 'subdomain', 'autosetup', 'servertype', 'servergroup', 'configoption1', 'configoption2', 'configoption3', 'configoption4', 'configoption5', 'configoption6', 'configoption7', 'configoption8', 'configoption9', 'configoption10', 'configoption11', 'configoption12', 'configoption13', 'configoption14', 'configoption15', 'configoption16', 'configoption17', 'configoption18', 'configoption19', 'configoption20', 'configoption21', 'configoption22', 'configoption23', 'configoption24', 'freedomain', 'freedomainpaymentterms', 'freedomaintlds', 'recurringcycles', 'autoterminatedays', 'autoterminateemail', 'configoptionsupgrade', 'billingcycleupgrade', 'upgradeemail', 'overagesenabled', 'overagesdisklimit', 'overagesbwlimit', 'overagesdiskprice', 'overagesbwprice', 'tax', 'affitiatepaytype', 'affiliateonetime', 'affiliatepayamount', 'order', 'retired', 'is_featured'];
    protected $date = ['created_at', 'updated_at'];

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

    public function group()
    {
        return $this->belongsTo("ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\ProductGroup", "gid");
    }

    public function upgrades()
    {
        return $this->hasMany("ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\ProductUpgrade", "product_id");
    }

}
