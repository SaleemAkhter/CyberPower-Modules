<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Models;

use \Illuminate\Database\Capsule\Manager as Capsule;
use \ModulesGarden\Servers\DirectAdminExtended\App\Helpers\CustomFieldsConstants;

class Hosting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tblhosting';
    public $customFields;
    public $configoption;

    public static function factory($id = null)
    {
        if ($id !== null)
        {
            $hosting = Hosting::where('id', $id)->first();

            return $hosting;
        }

        return new self();
    }

    function getProductServerType($pid = null)
    {
        $res = Capsule::table('tblproducts')
                ->select('servertype')
                ->where('id', $pid ? $pid : $this->packageid)
                ->first();

        return $res->servertype;
    }

    function getProductIdByHostingId($hid)
    {
        return Capsule::table('tblhosting')
                        ->where('id', $hid)
                        ->value('packageid');
    }

    function getServer($hid = null)
    {
        return Capsule::table('tblservers')
                        ->select('*')
                        ->where('id', $hid ? $hid : $this->server)
                        ->first();
    }
}
