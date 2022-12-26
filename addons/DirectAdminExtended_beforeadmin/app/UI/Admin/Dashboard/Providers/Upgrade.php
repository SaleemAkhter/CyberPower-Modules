<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Dashboard\Providers;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\DirectAdminExtended\Core\Models\Whmcs\Product;
use WHMCS\Database\Capsule as DB;

class Upgrade extends BaseDataProvider
{

    public function read()
    {
        $raw    = DB::raw("
          SELECT DISTINCT 
            tblservergroups.id,  tblservergroups.name
          FROM 
            tblservers 
          INNER JOIN 
            tblservergroupsrel
          ON
            tblservergroupsrel.serverid = tblservers.id
          INNER JOIN 
            tblservergroups
          ON
            tblservergroups.id = tblservergroupsrel.groupid
          WHERE 
            type = 'directadminExtended'
        ");

        $servers    = [];

        array_map(function($query) use(&$servers){
            $servers[$query->id]    = $query->name;
        },  DB::select($raw));



        $this->availableValues['serverGroup']    = $servers;
        $this->data['selectedProduct']           = $this->actionElementId;
    }

    public function create()
    {
        
    }

    public function update()
    {
        $product                = Product::findOrFail($this->formData['selectedProduct']);
        $product->servertype    = 'directadminExtended';
        $product->servergroup   = $this->formData['serverGroup'];
        $product->save();
    }

    public function delete()
    {
        
    }
}
