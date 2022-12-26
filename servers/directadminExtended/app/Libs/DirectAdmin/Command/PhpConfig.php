<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class PhpConfig extends AbstractCommand
{
    const CMD_PHP_SAFE_MODE  = 'CMD_PHP_SAFE_MODE';


    public function admin_getDomains()
    {

        $response = $this->curl->request(self::CMD_PHP_SAFE_MODE, [], [
            'ipp' => '100000',
            'json'=>'yes'
        ]);
        return $response;
    }
    public function disableOpenbasedir($selected)
     {
        $data=[
            'json'=>'yes',
            'action'=> 'set',
            'disable_obd'=>"yes"
        ];
        $data=array_merge($data,$selected);
        return $this->curl->request(self::CMD_PHP_SAFE_MODE, $data,[
                'json'=>'yes'
            ] );
    }
    public function enableOpenbasedir($selected)
     {
        $data=[
            'json'=>'yes',
            'action'=> 'set',
            'enable_obd'=>"yes"
        ];
        $data=array_merge($data,$selected);
        return $this->curl->request(self::CMD_PHP_SAFE_MODE, $data,[
                'json'=>'yes'
            ] );
    }

    public function changeNameservers($data)
     {
         return $this->curl->request(self::CMD_PHP_SAFE_MODE, [
                'ns1'=>trim($data['ns1']),
                'ns2'=>trim($data['ns2']),
                'action'=>'modify',
                'json'=>'yes'
            ],[
                'ipp' => '100000',
                'json'=>'yes'
            ] );
    }

}
