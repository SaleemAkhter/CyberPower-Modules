<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class MessageSystem extends AbstractCommand
{

    const CMD_TICKETS   ='CMD_API_TICKET';
    const CMD_JSON_LANG ="CMD_JSON_LANG";
    const CMD_TICKET="CMD_API_TICKET";

    /**
     * list reseller users
     *
     * [
            'json'=>'yes',
            'ipp'=>'500',
            'bytes'=>'yes',
            'key'=>'username',
            'order'=>'ASC',
            'sort1'=>'1'
        ]
     * @return mixed
     */

    public function info()
    {
/*CMD_JSON_LANG?json=yes&domain=site2.dmo.website&initial=yes&request=global&show_extra=yes
    CMD_JSON_LANG?json=yes&initial=yes&request=global&show_extra=yes
*/
        return  $this->curl->request(self::CMD_JSON_LANG,[],[
            'json'=>'yes',
            'initial'=>'yes',
            'request'=>'global',
            // 'show_extra'=>'yes'
        ]);
    }
    public function list()
    {
/*CMD_JSON_LANG?json=yes&domain=site2.dmo.website&initial=yes&request=global&show_extra=yes
    CMD_JSON_LANG?json=yes&initial=yes&request=global&show_extra=yes
*/
        switch ($_GET['iSortCol_0']) {
            case 'received':
                $key='last_message';
                $sort1=3;
                break;
            case 'subject':
                $key='subject';
                $sort1=2;
                break;
            default:
                $key='message';
                $sort1=1;
        }
        if($_GET['sSortDir_0']=="asc"){
            $sort1='3';
            $order="ASC";
        }else{
            $sort1=$sort1*-1;
            $order="DESC";
        }
        $length=$_GET['iDisplayLength'];
        $response = $this->curl->request(self::CMD_TICKETS,[],[
            'json'=>'yes',
            'ipp'=>$length,
            'type'=>'message',
            'key'=> $key,
            'order'=> $order,
            'sort1'=> $sort1
        ]);

        $list=$response->messages;

        return $list;

    }
    public function listdetail()
    {

        return  $this->curl->request(self::CMD_TICKETS,[],[
            'json'=>'yes',
            'ipp'=>5,
            'type'=>'message',
        ]);


    }
    public function clearMessages($data)
    {
        $response = $this->curl->request(self::CMD_TICKET,[],[
            'json'=>'yes',
            'subject_select'=>$data['subject_select'],
            'subject'=>$data['subject'],
            'when'=>$data['when'],
            'delete_messages_days'=>$data['delete_messages_days'],
            'delete'=>"Delete",
            'action'=> 'clear',
            'number'=> "$id"
        ]);
        return $response;
    }
    public function markReadMany($data)
    {
        return  $this->curl->request(self::CMD_TICKET,$data);
    }
    public function deleteMany($data)
    {
        return  $this->curl->request(self::CMD_TICKET,$data);
    }
    public function detail($id)
    {
        $response = $this->curl->request(self::CMD_TICKET,[],[
            'json'=>'yes',
            'type'=>'message',
            'action'=> 'view',
            'number'=> "$id"
        ]);
        if(isset($response->{0})){
            return $response->{0};
        }else{
            return false;
        }

    }

}
