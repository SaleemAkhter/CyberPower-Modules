<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 8, 2017)
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

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Models\Whmcs;

use Illuminate\Database\Capsule\Manager as DB;


/**
 * Description of Product
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
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
 * @property \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Models\ProductSettings\Model $setting
 */
class Product extends \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Models\Whmcs\Product
{

    /**
     *
     * @return \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Models\ProductSettings\Model
     */
    public function setting()
    {
        return $this->hasOne('\ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Models\ProductSettings\Model', 'product_id', 'id');
    }

    /**
     * @return []
     */
    public function getParams()
    {
        $statement = DB::connection()
            ->getPdo()
            ->prepare("SELECT s.ipaddress AS serverip, s.hostname AS serverhostname, s.username AS serverusername, s.password AS serverpassword, s.secure AS serversecure,
                                    s.accesshash AS serveraccesshash, s.id AS serverid,
                                    configoption1,configoption2,configoption3,configoption4,configoption5,configoption6,configoption7,configoption8,configoption9
                                    FROM tblservers AS s
                                     JOIN tblservergroupsrel AS sgr ON sgr.serverid = s.id
                                     JOIN tblservergroups AS sg ON sgr.groupid = sg.id
                                     JOIN tblproducts AS p ON p.servergroup = sg.id
                                    WHERE p.id = :pid 
                                   ORDER BY s.active DESC LIMIT 1");
        $statement->execute(["pid" => $this->id]);
        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        if (empty($row))
        {
            $s   = 'tblservers';
            $p   = 'tblproducts';
            $row = (array)DB::table($s)
                ->select("{$s}.ipaddress AS serverip", "{$s}.hostname AS serverhostname", "{$s}.username AS serverusername",
                    "{$s}.password AS serverpassword", "{$s}.secure AS serversecure", "{$s}.accesshash AS serveraccesshash")
                ->rightJoin($p, "{$p}.servertype", "=", "{$s}.type")
                ->where("{$p}.id", $this->id)
                ->orderBy("{$s}.active", 'desc')
                ->first();
        }
        $row['serverpassword'] = html_entity_decode(decrypt($row['serverpassword']),ENT_QUOTES);
        $row['packageid']      = $this->id;
        return $row;
    }

}
