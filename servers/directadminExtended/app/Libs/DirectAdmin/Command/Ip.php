<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Ip extends AbstractCommand
{
    const CMD_RESELLER_IPS  = 'CMD_API_SHOW_RESELLER_IPS';
    const CMD_IP_MANAGER    = 'CMD_IP_MANAGER';
    const CMD_IP_CONFIG     ='CMD_IP_CONFIG';



    /*Admin API*/
    public function admin_getIpListWithDetail()
    {

        $response = $this->curl->request(self::CMD_IP_MANAGER, [], [
            'ipp' => '100000',
            'json'=>'yes'
        ]);

        return $response;
    }

    public function admin_add($data)
    {


//{"ip":"193.31.29.62","netmask":"255.255.255.0","add_to_device":"yes","json":"yes","action":"add","add_to_device_aware":"yes"}{"ip":"193.31.29.61","netmask":"255.255.255.0","add_to_device":"yes","json":"yes","action":"add","add_to_device_aware":"yes"}
        return $this->curl->request(self::CMD_IP_MANAGER, [
            'ip'=>trim($data['ip']),
            'netmask'=>$data['netmask'],
            'add_to_device'=>'yes',
            'add_to_device_aware'=>'yes',
            'action'=>'add',
            'json'=>'yes'
        ],[
            'ipp' => '100000',
            'json'=>'yes'
        ] );

    }
    public function admin_delete($data)
    {
        return $this->curl->request(self::CMD_IP_MANAGER, [
            'select0'=>trim($data['ip']),
            'action'=>'select',
            'delete'=>'yes',
            'json'=>'yes'
        ],[
            'ipp' => '100000',
            'json'=>'yes'
        ] );

    }
    public function admin_deleteMany($data)
    {
        $selectedips=[
        'action'=>'select',
            'delete'=>'yes',
            'json'=>'yes'
        ];
        foreach ($data as $key => $ip) {
            $selectedips['select'.$key]=$ip;
        }

        return $this->curl->request(self::CMD_IP_MANAGER, $selectedips,[
            'ipp' => '100000',
            'json'=>'yes'
        ] );

    }
    public function admin_assign($data)
     {
         return $this->curl->request(self::CMD_IP_MANAGER, [
                'reseller'=>trim($data['reseller']),
                'select0'=>$data['select0'],
                'assign'=>'yes',
                'action'=>'select',
                'json'=>'yes'
            ],[
                'ipp' => '100000',
                'json'=>'yes'
            ] );
    }
    public function admin_remove($data)
     {
        if($data['reseller']=='all'){
            $data['reseller']='';
        }
         return $this->curl->request(self::CMD_IP_MANAGER, [
                'reseller'=>trim($data['reseller']),
                'select0'=>$data['select0'],
                'remove'=>'yes',
                'action'=>'select',
                'json'=>'yes'
            ],[
                'ipp' => '100000',
                'json'=>'yes'
            ] );
    }
    public function admin_clearns($data)
     {
         return $this->curl->request(self::CMD_IP_MANAGER, [
                'select0'=>$data['select0'],
                'clear'=>'yes',
                'action'=>'select',
                'json'=>'yes'
            ],[
                'ipp' => '100000',
                'json'=>'yes'
            ] );
    }
    public function admin_setglobal($data)
     {
         return $this->curl->request(self::CMD_IP_MANAGER, [
                'select0'=>$data['select0'],
                'global'=>'yes',
                'set_global'=>'yes',
                'action'=>'select',
                'json'=>'yes'
            ],[
                'ipp' => '100000',
                'json'=>'yes'
            ] );
    }
    public function admin_unsetglobal($data)
     {
         return $this->curl->request(self::CMD_IP_MANAGER, [
                'select0'=>$data['select0'],
                'global'=>'no',
                'set_global'=>'yes',
                'action'=>'select',
                'json'=>'yes'
            ],[
                'ipp' => '100000',
                'json'=>'yes'
            ] );
    }
    public function admin_share($data)
     {
         return $this->curl->request(self::CMD_IP_CONFIG, [
                'select0'=>$data['select0'],
                'share'=>'yes',
                'action'=>'select',
                'json'=>'yes'
            ],[
                'json'=>'yes'
            ] );
    }

    public function admin_free($data)
     {
         return $this->curl->request(self::CMD_IP_CONFIG, [
                'select0'=>$data['select0'],
                'free'=>'Free Selected',
                'action'=>'select',
                'json'=>'yes'
            ],[
                'ipp' => '100000',
                'json'=>'yes'
            ] );
    }
    /*Admin API ENDS*/
    /**
     * get ip list
     *
     * @return mixed
     */
    public function getIpList()
    {
        // comparison1: contains
        // comparison2: none
        // comparison3: none
        // comparison4: none
        // value1: 194.87.183.82
        // value2:
        // value3:
        // value4:
        // sort1dir: 1
        // sort1: 1
        // sort2dir: 1
        // ipp: 50

        $response = $this->curl->request(self::CMD_RESELLER_IPS, [], [
            'ipp' => '100000'
        ]);

        return $this->loadResponse(new Models\Command\Ip(), $response);
    }

    public function getIpListWithDetail()
    {
        $response = $this->getIpList();
        $ips=[];
        foreach ($response->response as $key => $address) {
            $ip=$this->getIpInformation($address)->toArray();
           $ip[0]['id']= $ip[0]['address']=$address->getAddress();
            $ips[]=$ip[0];
        }
        return $ips;
    }

    public function getFreeIp()
    {
        $response = $this->curl->request(self::CMD_IP_MANAGER, [] ,[
          'json' => 'yes',
          'ipp' => '100000'
        ]);

        return $this->loadResponse(new Models\Command\Ip(), $response, 'freeIp');
    }

    /**
     * get information about specific ip
     *
     * @param Models\Command\Ip $ip
     * @return mixed
     */
    public function getIpInformation(Models\Command\Ip $ip)
    {
        $response =  $this->curl->request(self::CMD_RESELLER_IPS, [
            'ip'    => $ip->getAddress()
        ]);

        return $this->loadResponse(new Models\Command\Ip(), $response);
    }

    public function configip($data)
    {
        return  $this->curl->request(self::CMD_IP_CONFIG ,$data);
    }
}
