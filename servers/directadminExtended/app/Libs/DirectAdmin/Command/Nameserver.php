<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Nameserver extends AbstractCommand
{
    const CMD_NAME_SERVER  = 'CMD_NAME_SERVER';


    public function admin_getIpListWithDetail()
    {

        $response = $this->curl->request(self::CMD_NAME_SERVER, [], [
            'ipp' => '100000',
            'json'=>'yes'
        ]);

        return $response;
    }
    public function create($data)
     {

         return $this->curl->request(self::CMD_NAME_SERVER, [
                'ns1'      => $data['ns1'],
                'ns2'      => $data['ns2'],
                'select0'      => $data['select0'],
                'select1'      => $data['select1'],
                'domain'      => $data['domain'],
                'virtual'      => $data['virtual'],
                'action'=>'select',
                'create'=>'yes',
                'json'=>'yes'
            ],[
                'json'=>'yes'
            ] );
    }
    public function delete($data)
     {
 // select0: 193.31.29.55
// ns1: ns1
// ns2: ns2
// domain: techsprinters.net
// delete: Delete Nameservers
// action: select
         return $this->curl->request(self::CMD_NAME_SERVER, [
                'ns1'      => $data['ns1'],
                'ns2'      => $data['ns2'],
                'select0'      => $data['select0'],
                'domain'      => $data['domain'],
                'action'=>'select',
                'delete'=>'Delete Nameservers',
                'json'=>'yes'
            ],[
                'json'=>'yes'
            ] );
    }
    public function changeNameservers($data)
     {
         return $this->curl->request(self::CMD_NAME_SERVER, [
                'ns1'=>trim($data['ns1']),
                'ns2'=>trim($data['ns2']),
                'action'=>'modify',
                'json'=>'yes'
            ],[
                'ipp' => '100000',
                'json'=>'yes'
            ] );
    }

//     delete: Delete Nameservers
// // action: select
}
