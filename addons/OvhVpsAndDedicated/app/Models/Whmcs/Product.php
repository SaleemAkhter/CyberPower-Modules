<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Models\Whmcs;

use ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\Product as CoreProduct;
use \ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\ServersRelations;
use Illuminate\Database\Capsule\Manager as DB;
/**
 * Class Product
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Product extends CoreProduct
{
//    public function serverRelations()
//    {
//        return $this->belongsToMany(Server::class, 'tblservergroupsrel','servergroup', 'servergroup');
//    }

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