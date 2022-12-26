<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class SshKey extends AbstractCommand
{
    const CMD_SSH_KEYS  = 'CMD_SSH_KEYS';


    public function admin_getKeys($authorized=false)
    {
        if($authorized){
            $tab='authorized';
        }else{
            $tab='public';
        }

        $response = $this->curl->request(self::CMD_SSH_KEYS, [], [
            'ipp' => '100000',
            'json'=>'yes',
            'tab'=>$tab
        ]);
        return $response;
    }
     public function getKeyDetail($fingerprint)
    {


        $response = $this->curl->request(self::CMD_SSH_KEYS, [], [
            'ipp' => '100000',
            'json'=>'yes',
            'enabled_users'=>'yes',
            'fingerprint'=>$fingerprint
        ]);
        return $response;
    }
    public function delete($selected)
     {
        $data=[
            'json'=>'yes',
            'action'=> 'set',
            'disable_obd'=>"yes"
        ];
        $data=array_merge($data,$selected);
        return $this->curl->request(self::CMD_SSH_KEYS, $data,[
                'json'=>'yes'
            ] );
    }
    public function authorize($selected)
     {
        $data=[
            'json'=>'yes',
            'action'=> 'select',
            'type'=> 'public',
            'authorize'=>'yes'
        ];
        $data=array_merge($data,$selected);
        return $this->curl->request(self::CMD_SSH_KEYS, $data,[
                'json'=>'yes'
            ]);
    }
    public function update($data)
     {
        return $this->curl->request(self::CMD_SSH_KEYS, $data,[
                'json'=>'yes'
            ]);
    }

    public function create($data)
     {
         return $this->curl->request(self::CMD_SSH_KEYS, $data,[
                'ipp' => '100000',
                'json'=>'yes'
            ] );
    }
    public function paste($data)
     {
         return $this->curl->request(self::CMD_SSH_KEYS, $data,[
                'ipp' => '100000',
                'json'=>'yes'
            ] );
    }

}
